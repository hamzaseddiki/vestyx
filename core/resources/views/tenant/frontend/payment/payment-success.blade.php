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
                    <div class="order-success-area margin-bottom-80 text-center">
                        <h1 class="title">{{get_static_option('site_order_success_page_' . $user_select_lang_slug . '_title')}}</h1>
                        <p class="order-page-description">{{get_static_option('site_order_success_page_' . $user_select_lang_slug . '_description')}}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="billing-title">{{__('Order Details')}}</h2>
                    <ul class="billing-details">
                        <li><strong>{{__('Order ID')}}</strong> #{{$payment_details->id}}</li>
                        <li><strong>{{__('Payment Package')}}</strong> {{$payment_details->package_name}}</li>
                        <li><strong>{{__('Payment Gateway')}}</strong> {{$payment_details->package_gateway}}</li>

                        @if(!empty($payment_details->coupon))
                        <li><strong>{{__('Paid Amount After Discount : ')}}</strong> {{ amount_with_currency_symbol(optional($payment_details->package)->price) }}</li>
                        @endif
                        <li><strong>{{__('Payment Status')}}</strong> {{$payment_details->payment_status}}</li>
                        <li><strong>{{__('Transaction id')}}</strong> {{$payment_details->transaction_id}}</li>
                    </ul>
                    <h2 class="billing-title">{{__('Billing Details')}}</h2>
                    <ul class="billing-details">
                        <li><strong>{{__('Name')}}</strong> {{$payment_details->name}}</li>
                        <li><strong>{{__('Email')}}</strong> {{$payment_details->email}}</li>
                    </ul>
                    <div class="btn-wrapper margin-top-40">
                        @if(auth()->guard('web')->check())
                            <a href="{{route('tenant.user.home')}}" class="boxed-btn">{{__('Go To Dashboard')}}</a>
                        @else
                            <a href="{{route('tenant.frontend.homepage')}}" class="boxed-btn">{{__('Back To Home')}}</a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 mt-3">
                    <div class="right-content-area">
                        <div class="single-price-plan-01">
                            <div class="right-content-area">
                                <div class="price-header">
                                    <h4 class="title">{{ $payment_details->package_name}}</h4>
                                    <div class="img-icon">
                                        {!! render_image_markup_by_attachment_id(optional($payment_details->package)->image) !!}
                                    </div>
                                </div>
                                <div class="price-wrap">
                                    <span class="price">{{amount_with_currency_symbol($payment_details->package_price)}}</span><span class="month">{{ $payment_details->type ?? '' }}</span>
                                </div>
                                <div class="price-body">
                                    @php
                                        $feat = optional($payment_details->package)->getTranslation('features',get_user_lang());
                                    @endphp

                                    <ul>
                                        @foreach(explode("\n",$feat) as $item)
                                        <li><i class="las la-check success"></i> {{$item}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
