@extends('landlord.frontend.user.dashboard.user-master')
@section('title')
   {{__('All Payment History')}}
@endsection

@section('page-title')
    {{__('All Payment History')}}
@endsection

@section('section')

    @if(count($payment_log_histories) > 0)
        <div class="table-responsive">

            <div class="alert alert-success">
                <h4 class="text-center ">{{__('All Payment History of')}} : {{$domain_name}}</h4>
            </div>
            <a href="{{ route('landlord.user.home') }}" class="btn btn-info btn-sm my-3">{{__('Go to Dashboard')}}</a>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">{{__('Package Order Info')}}</th>
                    <th scope="col">{{__('Payment Status')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($payment_log_histories as $data)
                    <tr>
                        <td>
                            <div class="user-dahsboard-order-info-wrap">
                                <h5 class="title">{{$data->package_name}}</h5>
                                <div class="div">
                                    <small class="d-block"><strong>{{__('Transaction ID')}} : </strong> {{$data->transaction_id}}</small>
                                    <small class="d-block"><strong>{{__('Package Price')}} : </strong> {{amount_with_currency_symbol($data->package?->price)}}</small>
                                    @if(!empty($data->coupon_discount))
                                        <small class="d-block"><strong>{{__('Coupon Discount')}} : </strong> {{amount_with_currency_symbol($data->coupon_discount)}}</small>
                                    @endif
                                    <small class="d-block"><strong>{{__('Paid Amount')}} : </strong> {{amount_with_currency_symbol($data->package_price)}}</small>
                                    <small class="d-block"><strong>{{__('Payment Gateway')}} : </strong> {{ str_replace('_',' ', ucwords($data->package_gateway)) }}</small>

                                    @php
                                        $tenant_relation = $data->tenant?->custom_domain;
                                        $domain_condition = !empty($tenant_relation->custom_domain) && $tenant_relation->custom_domain_status == 'connected' ? $tenant_relation->custom_domain :  $data->tenant_id.'.'.env('CENTRAL_DOMAIN')
                                    @endphp

                                    <small class="d-block mb-2"><strong>{{__('Domain')}} : <span class="text-primary">{{ $domain_condition }}</span></strong>
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

                                    <small class="d-block"><strong>{{__('Start Date:')}}</strong> {{date('d-m-Y',strtotime($data->start_date))}}</small>
                                    @if($data->status != 'trial')
                                        @php
                                            $expire_date_condition = $data->package?->type == 2  ? __('Lifetime') : date('d-m-Y',strtotime($data->expire_date));
                                        @endphp
                                      <small class="d-block"><strong>{{__('Expire Date:')}}</strong> {{ $expire_date_condition}}</small>
                                    @endif
                                    @if($data->status == 'trial')
                                        <small class="d-block"><strong>{{__('Trial Expire Date:')}}</strong> {{date('d-m-Y',strtotime($data->expire_date))}}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="user-dahsboard-status-info-wrap">
                            @if($data->payment_status == 'pending' && $data->status == 'pending')
                                <span class="alert alert-warning text-capitalize alert-sm">{{$data->payment_status}}</span>
                            @elseif($data->payment_status == 'pending' && $data->status == 'trial')
                                <span class="alert alert-warning text-capitalize alert-sm">{{$data->payment_status}}</span>
                            @else
                                @if($data->payment_status == 'complete')
                                    <span class="alert alert-success text-capitalize alert-sm d-inline-block">{{$data->payment_status}}</span>
                                @else
                                    <span class="alert alert-warning text-capitalize alert-sm d-inline-block">{{$data->payment_status}}</span>
                                @endif
                            @endif

                            @if($data->payment_status == 'complete' && $data->is_renew == 1)
                                    <span class="alert alert-primary text-capitalize alert-sm d-inline-block">{{__('Renewed')}}</span>
                            @endif
                        </td>


                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="blog-pagination">
            {{ $payment_log_histories->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('No Order Found')}}</div>
    @endif


@endsection


