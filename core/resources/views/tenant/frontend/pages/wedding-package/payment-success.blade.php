@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('Payment Success For:')}} {{$payment_details->package_name}}
@endsection

@section('page-title')
    {{__('Payment Success For:')}} {{$payment_details->package_name}}
@endsection
@section('content')
    <div class="error-page-content" data-padding-bottom="100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-success-area margin-bottom-80 margin-top-80 text-center">
                        <h1 class="title">{{__('Order Completed Successfully')}}</h1>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="billing-title my-2">{{__('Order Details')}}</h2>
                    <ul class="billing-details">
                        <li><strong>{{__('Order ID')}}</strong> : #{{$payment_details->id}}</li>
                        <li><strong>{{__('Payment Package')}}</strong> : {{$payment_details->package_name}}</li>
                        <li><strong>{{__('Payment Gateway')}}</strong> : {{$payment_details->package_gateway}}</li>
                        <li><strong>{{__('Payment Status')}}</strong> : {{$payment_details->payment_status}}</li>
                        <li><strong>{{__('Paid Amount')}}</strong> : {{amount_with_currency_symbol($payment_details->package_price)}}</li>
                        <li><strong>{{__('Transaction id')}}</strong> : {{$payment_details->transaction_id}}</li>
                    </ul>
                    <h2 class="billing-title my-2">{{__('Billing Details')}}</h2>
                    <ul class="billing-details">
                        <li><strong>{{__('Name')}}</strong> : {{$payment_details->name}}</li>
                        <li><strong>{{__('Email')}}</strong> : {{$payment_details->email}}</li>
                    </ul>
                    <div class="btn-wrapper margin-top-40">
                        @if(auth()->guard('web')->check())
                            <a href="{{route('tenant.user.home')}}" class="wedding_cmn_btn btn_gradient_main radius-30">{{__('Go To Dashboard')}}</a>
                        @else
                            <a href="{{route('tenant.frontend.homepage')}}" class="wedding_cmn_btn btn_gradient_main radius-30">{{__('Back To Home')}}</a>
                        @endif
                    </div>
                </div>

                @php
                    $popular_condition_text = $order_details->is_popular == 'on' ? __('Popular') : '';
                    $popular_condition = $order_details->is_popular == 'on' ? 'popular' : '';
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="wedding_package {{$popular_condition}} radius-10">
                        <div class="wedding_package__header">
                            <h4 class="wedding_package__name">{{$order_details->title}}<span class="popular__title">{{$popular_condition_text}}</span></h4>
                            <h5 class="wedding_package__price"><sup>{{ site_currency_symbol() }}</sup>{{$order_details->price}}</h5>
                        </div>
                        <div class="wedding_package__body mt-4">
                            <ul class="wedding_package__list list-none">
                                @foreach(explode("\n",$order_details->features) ?? [] as $feature)
                                    <li class="check_icon">{{$feature}}</li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
