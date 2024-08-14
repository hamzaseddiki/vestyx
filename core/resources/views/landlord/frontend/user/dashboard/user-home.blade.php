@extends('landlord.frontend.user.dashboard.user-master')
@section('page-title')
    {{__('User Home')}}
@endsection

@section('title')
    {{__('User Home')}}
@endsection

@section('style')
    <style>
        .badge{
            font-size: 15px;
        }

        .form-group.domain_area {
            margin: 15px 0;
        }

    </style>
@endsection

@section('section')
    @php
        $auth_user = \Illuminate\Support\Facades\Auth::guard('web')->user();
    @endphp
    <div class="row g-4 ">
        <div class="col-md-12">
            <div class="btn-wrapper mb-3 mt-2 float-right" >
                <a href="#" class="btn btn-success mx-2" data-bs-toggle="modal" data-bs-target="#create_website_modal">{{__('Create a website')}}</a>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">
                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$package_orders ?? ''}} </h2>
                        <span class="order-para">{{__('Total Orders')}} </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">
                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$support_tickets ?? ''}} </h2>
                        <span class="order-para">{{__('Support Tickets')}} </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-5">
            <div class="subdomains mb-5">
                <h4 class="mb-3 text-uppercase text-center">{{__('Your Website')}}</h4>
                <div class="payment">
                    <table class="table table-responsive table-bordered recent_payment_table">
                        <thead>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Site')}}</th>
                        <th>{{__('Browse')}}</th>
                        </thead>
                        <tbody class="w-100">
                        @php
                            $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
                        @endphp

                        @foreach($user->tenant_details ?? [] as $key => $data)
                            @php
                                $tenantHelper = \App\Helpers\TenantHelper\TenantHelpers::init()->setTenantId($data->id);
                            @endphp
                            <tr>
                                <td>{{$key +1}}</td>
                                <td>
                                    @php
                                        $url = '';
                                        $central = '.'.env('CENTRAL_DOMAIN');

                                        if(!empty($data->custom_domain?->custom_domain) && $data->custom_domain?->custom_domain_status == 'connected'){
                                            $custom_url = $data->custom_domain?->custom_domain ;
                                            $url = tenant_url_with_protocol($custom_url);
                                        }else{
                                            $local_url = $data->id .$central ;
                                            $url = tenant_url_with_protocol($local_url);
                                        }
                                         $hash_token = hash_hmac('sha512',$user->username.'_'.$data->id,$data->unique_key);
                                    @endphp
                                  <a class="badge rounded-pill bg-success">{{$url}}</a>
                                </td>
                                <td>
                                    <a class="badge rounded-pill bg-primary px-4" href="{{$url}}" target="_blank">{{__('Visit Website')}}</a>
                                    <a class="badge rounded-pill bg-info px-4 mt-2" href="{{$url.'/token-wise-login/'.$hash_token}}" target="_blank">{{__('Login as super admin')}}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <h4 class="mb-3 text-uppercase text-center">{{__('Recent Orders')}}</h4>
            <div class="payment">
                <table class="table table-responsive table-bordered recent_payment_table">
                    <thead>
                    <th>{{__('ID')}}</th>
                    <th>{{__('Package Name')}}</th>
                    <th>{{__('Amount')}}</th>
                    <th>{{__('Domain')}}</th>
                    <th>{{__('Start Date')}}</th>
                    <th>{{__('Expire Date')}}</th>
                    <th>{{__('Order Status')}}</th>
                   <th>{{__('Payment History')}}</th>

                    </thead>
                    <tbody class="w-100">
                    @foreach($recent_logs as $key=> $data)

                        @php
                            $tenantHelperForLog = \App\Helpers\TenantHelper\TenantHelpers::init()->setTenantId($data->tenant_id);
                            $paymentLogHistory = \App\Models\PaymentLogHistory::where('user_id', Auth()->guard('web')->user()->id)->where('tenant_id',$data->tenant_id)->first();
                        @endphp
                        <tr>
                            <td>{{$key +1}}</td>
                            <td>{{$data->package_name}}</td>
                            <td>{{ amount_with_currency_symbol($data->package_price) }}</td>
                            <td>{{ $data->tenant_id . '.' . env('CENTRAL_DOMAIN') }}</td>
                            <td>
                                {{$tenantHelperForLog->getTenantStartDate()}}

                            </td>
                            <td>
                                {{$tenantHelperForLog->getTenantExpiredDate() }}
                            </td>
                            <td>{{$data->status}}</td>
                            <td>
                                @if(!empty($data->payment_status) && $data->payment_status == 'complete' && !is_null($tenantHelperForLog->getTenant()) && $paymentLogHistory)
                                <a href="{{route('landlord.user.dashboard.payment.log.history',$data->tenant_id)}}" class="btn btn-success btn-sm">{{__('View Details')}}</a>
                                @elseif( in_array($data->payment_status,['pending','complete']) && $data->status === 'trial' && is_null($tenantHelperForLog->getTenant()))
                                    {{__('Waiting For Admin Approval')}}
                                @elseif( is_null($paymentLogHistory))
                                    {{__('No Payment History Found')}}
                                @elseif($data->status == 'complete' && $data->payment_status == 'complete' && $paymentLogHistory)
                                    <a href="{{route('landlord.user.dashboard.payment.log.history',$data->tenant_id)}}" class="btn btn-success btn-sm">{{__('View Details')}} </a>
                                @elseif($data->status !== 'trial')
                                    {{$data->payment_status }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <div class="modal fade" id="create_website_modal" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Create Quick Website')}}</h5>
                    <button type="button" class="close bg-danger text-white" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('landlord.frontend.order.payment.form')}}" method="post" enctype="multipart/form-data" class="register_page_payment_hidden_form" >
                    @csrf
                    @php
                        $auth_user = auth()->guard('web')->user();
                    @endphp

                    <input type="hidden" name="user_id" value="{{$auth_user->id}}">
                    <input type="hidden" name="quick_web_site" value="quick_web_site">
                    <input type="hidden" name="theme_slug" class="theme_slug">
                    <input type="hidden" name="theme_code" class="theme_code">

                    <input type="hidden" name="name" value="{{$auth_user->name}}">
                    <input type="hidden" name="email" value="{{$auth_user->email}}">
                    <input type="hidden" class="package_price">
                    <input type="hidden" class="package_id">

                    <div class="modal-body">
                        <div class="form-group user_home">
                            <strong for="">{{__('Select A Package')}}</strong>
                            <select class="form-control package_id_selector niceSelect_active" name="package_id">
                                <option value="">{{__('Select Package')}}</option>
                                @foreach($price_plans as $price)

                                    @continue($price->type == 3)
                                    <option value="{{$price->id}}" data-price="{{$price->price}}" data-id="{{$price->id}}" data-type="{{$price->type}}" data-has_trial="{{$price->has_trial}}">
                                        {{$price->getTranslation('title',get_user_lang())}} {{ '('.amount_with_currency_symbol($price->price).')' }}
                                        @if($price->has_trial == 1)
                                            ({{__('Available for Trial') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <br>

                        <div class="form-group domain_area">
                            <strong for="name">{{__('Enter your Domain')}}</strong>
                            <input type="text" class="form-control" name="subdomain" placeholder="Your domain">
                        </div>

                        <div id="subdomain-wrap" class="mb-3"></div>

                        <div class="form-group theme_parent">
                            <strong for="">{{__('Select Theme')}}</strong>
                            <select class="form-control theme_slug" name="theme">
                                <option selected disabled>{{__('Select a theme')}}</option>
                            </select>
                        </div>

                        <div class="form-group quick_trial_status_container d-none">
                            <strong for="">{{__('Select Payment Method')}}</strong>
                            <select name="quick_trial_status" class="form-control quick_trial_status ">
                                <option value="paid_subscription">{{__('Paid Subscription')}}</option>
                                <option value="trial">{{__('Only Trial')}}</option>
                            </select>
                        </div>

                        @php
                            $all_gateways = \App\Models\PaymentGateway::where('status',1)->get();
                        @endphp

                        <div class="form-group payment_parent">
                            <strong for="">{{__('Select Payment Gateway')}}</strong>
                            <select name="selected_payment_gateway" class="form-control payment_gateway">
                                <option selected disabled>{{__('Select Payment Gateway')}}</option>
                                @foreach($all_gateways as $payment)
                                    <option value="{{$payment->name}}" data-gateway="{{$payment->name}}">{{ str_replace('_',' ',ucwords($payment->name)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group bank_payment_field d-none">
                            <div class="label mb-2">{{__('Attach your bank Document')}}</div>
                            <input class="form-control btn-sm mb-3 py-3 p-3" type="file" name="manual_payment_attachment">
                            <p class="help-info my-3">{!! get_bank_payment_description() !!}</p>
                        </div>

                        <div class="form-group manual_payment_transaction_field d-none">
                            <div class="label mb-2">{{__('Enter Transaction ID')}}</div>
                            <input class="form-control btn-sm mb-3 py-3 p-3" type="text" name="transaction_id">
                            <p class="help-info my-3">{!! get_manual_payment_description() !!}</p>
                        </div>


                        @if(!empty(get_static_option('coupon_apply_status')))
                            <div class="col-lg-12 my-3 coupon_parent d-none">
                                <div class="error-wrap"></div>
                                <strong class="">{{__('Coupon Code')}}</strong>
                                <div class="input-group input-form2 mt-1">
                                    <input type="text" name="coupon_code" class="form-control coupon_code" placeholder="Enter Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2" style="height: 45px">
                                    <button class="btn btn-outline-secondary coupon_apply_button" type="submit" >{{__('Apply')}}</button>
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-12 col-md-12 payable_amount_parent d-none">
                            <div class="input-form input-form2 mt-3">
                                <strong class=>{{__('Payable Amount')}}</strong>
                                <input class="mt-2 payable_amount" name="payable_amount" type="text" value="0" readonly style="font-weight: bold; font-size: 28px">
                            </div>
                        </div>

                      </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-success">{{__('Create')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <x-unique-domain-checker :name="'subdomain'"/>

    <script>
        $(document).on('change','.theme_slug',function(){
           let theme_slug = $(this).val();
           let theme_code = $(this).find(':selected').data('theme_code');

            $('.theme_code').val(theme_code);
            $('.theme_slug').val(theme_slug);

        });

        $(document).on('change','.package_id_selector',function(){
            let trial = $(this).find(':selected').data('has_trial');
            let price = $(this).find(':selected').data('price');
            let id = $(this).find(':selected').data('id');

            $('.package_price').val(price);
            $('.payable_amount').val(price);
            $('.package_id').val(id);

            $.ajax({
                url: '{{ route('landlord.user.quick.website.theme.via.ajax') }}',
                type: 'get',
                data:{package_id:id},
                success: function (data){

                   $('.theme_slug').html(data.theme_markup);
                   // $('.payment_gateway').html(data.payment_markup);

                },
                error: function (error){
                    console.log(error);
                }
            });

             if(trial == 1){
                 $('.payment_parent').addClass('d-none');
                  $('.quick_trial_status_container').removeClass('d-none');
                 $('.payable_amount_parent').addClass('d-none');
                 $('.coupon_parent').addClass('d-none');
             }else{
                 $('.quick_trial_status_container').addClass('d-none');
                 $('.payment_parent').removeClass('d-none');
                 $('.payable_amount_parent').removeClass('d-none');
                 $('.coupon_parent').removeClass('d-none');
            }

             if(price == 0){
                 $('.payable_amount_parent').addClass('d-none');
                 $('.coupon_parent').addClass('d-none');
                 $('.payment_parent').addClass('d-none');
             }else{
                 $('.payable_amount_parent').removeClass('d-none');
                 $('.coupon_parent').removeClass('d-none');
                 $('.payment_parent').removeClass('d-none');
             }

        });


        $(document).on('change','.payment_gateway',function(){
            let gateway = $(this).val();

            if(gateway == 'bank_transfer'){
                $('.bank_payment_field').removeClass('d-none');
            }else{
                $('.bank_payment_field').addClass('d-none');
            }

            if(gateway == 'manual_payment_'){
                $('.manual_payment_transaction_field').removeClass('d-none');
            }else{
                $('.manual_payment_transaction_field').addClass('d-none');
            }

        });


        $(document).on('change','.quick_trial_status',function(){
            let value = $(this).val();

            if(value == 'paid_subscription'){
                $('.payment_parent').removeClass('d-none');
                $('.payable_amount_parent').removeClass('d-none');
                $('.coupon_parent').removeClass('d-none');
            }else{
                $('.payment_parent').addClass('d-none');
                $('.payable_amount_parent').addClass('d-none');
                $('.coupon_parent').addClass('d-none');
            }

        });


        //Coupon code
        $(document).on('click', '.coupon_apply_button', function (e) {
            e.preventDefault();
            var formContainer = $('.register_page_payment_hidden_form');
            var el = $(this);
            var package_price = $('.package_price').val();

            var coupon_code = formContainer.find('input[name="coupon_code"]').val();

            el.text('{{__("Please Wait..")}}');

            $.ajax({
                type: 'get',
                url: "{{route('landlord.coupon.ajax.check')}}",
                data: {
                    package_price: package_price,
                    coupon_code: coupon_code,
                },
                success: function (data) {
                    el.text('{{__("Apply")}}')

                    let coupon_field_value = $('.coupon_code');

                    if (data.status == 'invalid') {
                        coupon_field_value.val('');
                        formContainer.find('.error-wrap').html('<div class="alert alert-danger">' + data.msg + '</div>');
                        $('.payable_amount').val(package_price);
                    }

                    if (data.status == 'expired') {
                        coupon_field_value.val('');
                        formContainer.find('.error-wrap').html('<div class="alert alert-warning">' + data.msg + '</div>');
                        $('.payable_amount').val(package_price);
                    }

                    if (data.status == 'limit_over') {
                        coupon_field_value.val('');
                        formContainer.find('.error-wrap').html('<div class="alert alert-danger">' + data.msg + (data.limit) +'</div>');
                        $('.payable_amount').val(package_price);
                    }

                    if (data.status == 'applied') {
                        formContainer.find('.error-wrap').html('<div class="alert alert-success">' + data.msg + '</div>');

                        if(data.price == 0){

                            $('.payment_parent').addClass('d-none');
                            $('.coupon_parent').addClass('d-none');
                        }else{
                            $('.payment_parent').removeClass('d-none');
                            $('.coupon_parent').removeClass('d-none');
                        }

                        $('.payable_amount').val(data.price);
                    }
                },
                error: function (data) {
                    var response = data.responseJSON.errors;
                    formContainer.find('.error-wrap').html('<ul class="alert alert-danger"></ul>');
                    $.each(response,function (value,index){
                        formContainer.find('.error-wrap ul').append('<li>'+index[0]+'</li>');
                    });

                    el.text('{{__("Apply")}}');
                }
            });
        });
        //Coupon code

        $('.close-bars, .body-overlay').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $('.sidebar-icon').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });

    </script>

@endsection





