@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('My Payment Logs')}}
@endsection

@section('style')
    <x-datatable.css/>

@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    <x-error-msg/>
                    <x-flash-msg/>
                    <x-admin.header-wrapper>
                        <x-slot name="left">
                            <h4 class="card-title">{{__('Your Package Order Payment logs')}} {{__('(from main site)')}}</h4>
                        </x-slot>
                        <x-slot name="right" class="d-flex">
                            <x-link-with-popover url="{{route('landlord.homepage') .'#price_plan_section'}}" target="_blank">
                                {{__('Buy New Plan')}}
                            </x-link-with-popover>
                        </x-slot>

                    </x-admin.header-wrapper>


                    <div class="table-wrap table-responsive">
                        <table class="table table-default table-striped table-bordered">
                            <thead class="text-white">
                            <tr>
                                <th scope="col">{{__('SL #')}}</th>
                                <th scope="col">{{__('Package Order Info')}}</th>
                                <th scope="col">{{__('Payment Status')}}</th>
                                <th scope="col">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($package_orders ?? [] as $key => $data)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>
                                        <div class="user-dahsboard-order-info-wrap">

                                            @php
                                                $tenant_relation = $data->tenant?->custom_domain;
                                                $domain_condition = !empty($tenant_relation->custom_domain) && $tenant_relation->custom_domain_status == 'connected'
                                                ? $tenant_relation->custom_domain :  $data->tenant_id.'.'.env('CENTRAL_DOMAIN');
                                            @endphp

                                            <h4 class="title"><strong >{{__('Domain:')}}</strong> <span class="text-primary">{{$domain_condition }}</span></h4>


                                            <h6 class="title">{{$data->package_name}}</h6>
                                            <div class="div">
                                                <small class="d-block"><strong>{{__('Order ID:')}}</strong> #{{$data->id}}</small>
                                                <small class="d-block"><strong>{{__('Transaction ID')}} : </strong> {{$data->transaction_id}}</small>

                                                <small class="d-block"><strong>{{__('Package Price:')}}</strong> {{amount_with_currency_symbol($data->package?->price)}}</small>
                                                @if(!empty($data->coupon_discount))
                                                    <small class="d-block"><strong>{{__('Coupon Discount:')}}</strong> {{amount_with_currency_symbol($data->coupon_discount)}}</small>
                                                @endif
                                                <small class="d-block"><strong>{{__('Paid Amount:')}}</strong> {{amount_with_currency_symbol($data->package_price)}}</small>
                                                <small class="d-block"><strong>{{__('Payment Gateway')}} : </strong> {{ $data->package_gateway ?? __('No Gateway') }}</small>

                                                <small class="d-block"><strong>{{__('Order Status:')}}</strong>
                                                    @if($data->status == 'pending')
                                                        <span class="alert alert-warning text-capitalize alert-sm alert-small customAlert2">{{__($data->status )}}</span>
                                                    @elseif($data->status == 'cancel')
                                                        <span class="alert alert-danger text-capitalize alert-sm alert-small customAlert2">{{__($data->status)}}</span>
                                                    @elseif($data->status == 'in_progress')
                                                        <span class="alert alert-info text-capitalize alert-sm alert-small customAlert2">{{str_replace('_',' ',$data->status)}}</span>
                                                    @else
                                                        <span class="alert alert-success text-capitalize alert-sm alert-small customAlert2">{{$data->status }}</span>
                                                    @endif
                                                </small>

                                                <small class="d-block"><strong>{{__('Order Date:')}}</strong> {{date_format($data->created_at,'D m Y')}}</small>
                                                <small class="d-block"><strong>{{__('Start Date:')}}</strong> {{$data->start_date ?? ''}}</small>
                                                <small class="d-block"><strong>{{__('Expire Date:')}}</strong>
                                                    @if(!empty($data->expire_date))
                                                        {{ date('d-m-Y', strtotime($data->expire_date))  }}
                                                    @endif
                                                    @if(!empty($data->trial_expire_date) && $data->status == 'trial')
                                                        {{date('d-m-Y', strtotime($data->trial_expire_date))}}
                                                    @endif

                                                    @if( $data->package?->type == 2)
                                                        {{ __('Lifetime') }}
                                                    @endif

                                                </small>

                                                <a href="{{route('tenant.admin.my.package.payment.log.history',$data->tenant_id)}}" class="btn btn-info btn-sm my-2">{{__('View All Payment Details')}}</a>

                                                @if($data->payment_status == 'complete')
                                                    <form action="{{route(route_prefix().'my.package.invoice.generate')}}"  method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                                        <button class="btn btn-success btn-xs btn-small margin-top-10" type="submit">{{__('Invoice')}}</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>


                                    <td class="flexItems">
                                        @if($data->status == 'pending' || $data->status == 'trial')
                                            <span class="alert alert-warning text-capitalize alert-sm paymentBtn">{{$data->payment_status ?? __('Pending')}}</span>
                                            <a href="{{route('landlord.frontend.plan.order',$data->package_id)}}" class="btn btn-success btn-sm" target="_blank">{{__('Pay or Extend Package')}}</a>
                                        @else
                                            <span class="alert alert-success text-capitalize alert-sm" style="display: inline-block">{{$data->payment_status ?? __('Complete')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if( $data->status != 'trial' && $data->status != 'cancel')
                                            <a href="" data-bs-toggle="modal" data-bs-target="#renew_payment_form"
                                               class="btn btn-info btn-sm renew_btn"
                                               target="_blank"
                                               data-log_id = "{{$data->id}}"
                                               data-package_id = "{{$data->package_id}}"
                                            >{{__('Renew')}}</a>
                                        @endif

                                        @if(!empty(get_static_option_central('cancel_subscription_status')))
                                            @if( $data->status != 'cancel' && $data->status != 'trial')
                                                <x-confirm-popover :url="route('tenant.admin.package.order.cancel',$data->id)" title="Cancel Subscription"/>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="renew_payment_form" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Select Payment Gateway')}}</h5>
                    <button type="button" class="close btn btn-danger btn-sm" data-bs-dismiss="modal">X</button>
                </div>

                <div class="modal-body">
                    <form action="{{route('tenant.admin.my.package.renew.process')}}" method="post" target="_blank" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="package_id" class="order_package_id">
                        <input type="hidden" name="log_id" class="order_log_id">
                        <input type="hidden" name="manual_payment_attachment" class="manual_payment_attachment">

                        <select name="payment_gateway" class="form-control payment_gateway">
                            <option selected disabled>{{__('Select Payment Gateway')}}</option>
                            @foreach(\App\Models\PaymentGateway::all() as $gateway)
                                <option value="{{$gateway->name}}">{{ str_replace('_', ' ', ucfirst($gateway->name)) }}</option>
                            @endforeach
                        </select>

                        <div class="form-group bank_payment mt-4 d-none">
                            <label class="label mb-2">{{__('Attach your bank Document')}}</label>
                            <input class="form-control mb-3 py-3 p-3" type="file" name="manual_payment_attachment">
                            <p class="help-info my-3">{!! get_bank_payment_description() !!}</p>
                        </div>

                        <div class="form-group manual_payment_transaction_field mt-4 d-none">
                            <label class="label mb-2">{{__('Transaction ID')}}</label>
                            <input class="form-control mb-3 py-3 p-3" type="text" name="transaction_id">
                            <p class="help-info my-3">{!! get_manual_payment_description() !!}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm renew_modal">{{__('Go To Payment')}}</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <x-datatable.js/>

    <script>
        $(document).on('click','.renew_btn',function(){
            $('.order_package_id').val($(this).data('package_id'));
            $('.order_log_id').val($(this).data('log_id'));
        });

        $(document).on('change', '.payment_gateway', function () {
            let el = $(this);

            if(el.val() == 'manual_payment_'){
                $('.manual_payment_transaction_field').removeClass('d-none');
            }else{
                $('.manual_payment_transaction_field').addClass('d-none');
            }

            if(el.val() == 'bank_transfer'){
                $('.bank_payment').removeClass('d-none');
            }else{
                $('.bank_payment').addClass('d-none');
            }
        });


        $(document).on('click','.swal_change_confirm_button',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure to cancel this subscription?")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, Cancel it!')}}",
                cancelButtonText : "{{__('No')}}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });



    </script>

@endsection

