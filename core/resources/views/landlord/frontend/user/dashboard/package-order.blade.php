@extends('landlord.frontend.user.dashboard.user-master')
@section('title')
   {{__('Payment Logs')}}
@endsection

@section('page-title')
    {{__('Payment Logs')}}
@endsection


@section('section')

    @if(count($package_orders) > 0)
        <div class="table-responsive ">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">{{__('Package Order Info')}}</th>
                    <th scope="col">{{__('Payment Status')}}</th>
                    <th scope="col">{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($package_orders as $data)
                    @php
                        $tenantHelper = \App\Helpers\TenantHelper\TenantHelpers::init()->setTenantId($data->tenant_id);
                    @endphp
                    <tr>
                        <td>
                            <div class="user-dahsboard-order-info-wrap">


                                @php
                                    $tenant_relation = $data->tenant?->custom_domain;
                                    $domain_condition = !empty($tenant_relation->custom_domain) && $tenant_relation->custom_domain_status == 'connected' ? $tenant_relation->custom_domain :  $data->tenant_id.'.'.env('CENTRAL_DOMAIN')
                                @endphp

                                <h5 class="title">{{__('Domain')}} : <span class="text-primary">{{ $domain_condition }}</span></h5>

                                <h6 class="title">{{$data->package_name}}</h6>
                                <div class="div">
                                    <small class="d-block"><strong>{{__('Order ID')}} : </strong> #{{$data->id}}</small>
                                    <small class="d-block"><strong>{{__('Transaction ID')}} : </strong> {{$data->transaction_id}}</small>
                                    <small class="d-block"><strong>{{__('Package Price')}} : </strong> {{amount_with_currency_symbol($data->package?->price)}}</small>
                                    @if(!empty($data->coupon_discount))
                                        <small class="d-block"><strong>{{__('Coupon Discount')}} : </strong> {{amount_with_currency_symbol($data->coupon_discount)}}</small>
                                    @endif
                                    <small class="d-block"><strong>{{__('Paid Amount')}} : </strong> {{amount_with_currency_symbol($data->package_price)}}</small>
                                    <small class="d-block"><strong>{{__('Payment Gateway')}} : </strong> {{ str_replace('_',' ', ucwords($data->package_gateway)) }}</small>


                                    <small class="d-block"><strong>{{__('Order Status')}} : </strong>
                                        @if($data->status == 'pending')
                                            <span class="alert alert-warning text-capitalize alert-sm alert-small">{{__($data->status )}}</span>
                                        @elseif($data->status == 'cancel')
                                            <span class="alert alert-danger text-capitalize alert-sm alert-small">{{__($data->status)}}</span>
                                        @elseif($data->status == 'in_progress')
                                            <span class="alert alert-info text-capitalize alert-sm alert-small">{{str_replace('_',' ',$data->status)}}</span>
                                        @elseif($data->status == 'trial')
                                            <span class="alert alert-danger text-capitalize alert-sm alert-small">{{str_replace('_',' ',$data->status)}}</span>
                                        @else
                                            <span class="alert alert-success text-capitalize alert-sm alert-small">{{$data->status }}</span>
                                        @endif
                                    </small>

                                    <small class="d-block"><strong>{{__('Start Date:')}}</strong> {{ $tenantHelper->getTenantStartDate() }}</small>
                                    @if($data->status != 'trial')
                                        @php

                                            $expire_date_condition = $data->package?->type == 2  ? __('Lifetime') : date('d-m-Y',strtotime($data->expire_date));
                                        @endphp
                                      <small class="d-block"><strong> {{$tenantHelper->getTenantExpiredDate() }} </strong> {{ $expire_date_condition}} </small>
                                    @endif
                                    @if($data->status == 'trial')
                                        <small class="d-block"><strong>{{__('Trial Expire Date:')}}</strong> {{date('d-m-Y',strtotime($data->expire_date))}}</small>
                                    @endif

                                    @if($data->package_gateway != 'free')
                                        <a href="{{route('landlord.user.dashboard.payment.log.history',$data->tenant_id)}}" class="btn btn-success btn-sm my-2">{{__('View All Payment Details')}}</a>
                                    @endif

                                    @if($data->payment_status == 'complete' && $data->status != 'trial')
                                        <form action="{{route('landlord.frontend.package.invoice.generate')}}"  method="post">
                                            @csrf
                                            <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                            <button class="btn btn-secondary btn-xs btn-small margin-top-10" type="submit">{{__('Invoice')}}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="user-dahsboard-status-info-wrap">
                            @if($data->payment_status == 'pending' && $data->status == 'pending')
                                <span class="alert alert-warning text-capitalize alert-sm">{{$data->payment_status}}</span>
                                <a href="{{route('landlord.frontend.plan.order',$data->package_id)}}" class="btn btn-success btn-sm">{{__('Pay Now')}}</a>
                            @elseif($data->payment_status == 'pending' && $data->status == 'trial')
                                <span class="alert alert-warning text-capitalize alert-sm">{{$data->payment_status}}</span>
                                <a href="{{route('landlord.frontend.plan.order',$data->package_id)}}" class="btn btn-success btn-sm" target="_blank">{{__('Pay or Extend Package')}}</a>
                            @else
                                @if($data->payment_status == 'complete')
                                    <span class="alert alert-success text-capitalize alert-sm d-inline-block">{{$data->payment_status}}</span>
                                @else
                                    <span class="alert alert-warning text-capitalize alert-sm d-inline-block">{{$data->payment_status}}</span>
                                @endif
                            @endif
                        </td>

                        <td>
                            @if($data->status != 'trial' && $data->status != 'cancel' && $data->payment_status == 'complete')
                                <a href="" data-bs-toggle="modal" data-bs-target="#renew_payment_form"
                                   class="btn btn-info btn-sm renew_btn "
                                   target="_blank"
                                   data-log_id = "{{$data->id}}"
                                   data-tenant_id = "{{$data->tenant_id}}"
                                   data-package_id = "{{$data->package_id}}"
                                >{{__('Renew Now')}}</a>

                                @if(!empty(get_static_option_central('cancel_subscription_status')))
                                    <x-confirm-popover :url="route('landlord.user.dashboard.package.order.cancel',$data->id)" title="Cancel Subscription"/>
                                @endif
                            @endif

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="blog-pagination">
            {{ $package_orders->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('No Order Found')}}</div>
    @endif

    <div class="modal fade" id="renew_payment_form" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Select Payment Gateway')}}</h5>
                    <button type="button" class="close btn btn-danger btn-sm" data-bs-dismiss="modal">X</button>
                </div>

                <div class="modal-body">
                    <form action="{{route('landlord.user.package.renew.process')}}" method="post" target="_blank" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="package_id" class="order_package_id">
                        <input type="hidden" name="log_id" class="order_log_id">
                        <input type="hidden" name="tenant_id" class="tenant_id">

                        @php
                            $all_gateways = \App\Models\PaymentGateway::where('status',1)->get();
                        @endphp

                        <select name="payment_gateway" class="form-control payment_gateway niceSelect_active">
                            <option selected disabled>{{__('Select Payment Gateway')}}</option>
                            @foreach($all_gateways as $gateway)
                                <option value="{{$gateway->name}}">{{ str_replace('_', ' ', ucfirst($gateway->name)) }}</option>
                            @endforeach
                        </select>
                        <br>
                        <br>

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
    <script src="{{global_asset('assets/landlord/common/js/sweetalert2.js')}}"></script>
    <script>


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


        $('.close-bars, .body-overlay').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $('.sidebar-icon').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });

        $(document).on('click','.renew_btn',function(){
            $('.order_package_id').val($(this).data('package_id'));
            $('.order_log_id').val($(this).data('log_id'));
            $('.tenant_id').val($(this).data('tenant_id'));
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


    </script>
@endsection
