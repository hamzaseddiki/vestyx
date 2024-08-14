@extends('tenant.frontend.user.dashboard.user-master')

@section('title')
    {{__('Order Details')}}
@endsection

@php
    $order_meta = json_decode($order->payment_meta);
@endphp

@section('section')
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/module-fix-style.css')}}">
        <!-- Order status start-->
        <div class="order-status-wrap order-details-page">
            <table class="order-status-inner">
                <tbody>
                <tr class="complete">
                    <td>
                        <span class="order-number"> {{__("order")}} #{{ $order->id }}</span>
                        <span class="price">{{ amount_with_currency_symbol($order->total_amount) }}</span>
                    </td>
                    <td>
                        <span class="date">{{ $order->created_at?->format("M d, Y") }}</span>
                        <span class="time">{{ $order->created_at?->format("H:ia") }}</span>
                    </td>
                    <td>
                        <div class="btn-wrapper">
                            <span class="order-btn-custom status">{{__('Order Status')}} : {{ $order->status }}</span>
                            <span class="order-btn-custom status">{{__('Payment Status')}} : {{ $order->payment_status }}</span>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- Order status end-->

        <!-- Order summery start -->
        <div class="order-inner-content-wrap">
            <h4 class="title">{{__("order details")}}</h4>
            <div class="billing-info">
                <div class="date-time">
                    <span class="date">{{ $order->created_at?->format("M d, Y") }}</span>
                    <span class="time">{{ $order->created_at?->format("H:ia") }}</span>
                </div>

                <div class="address">
                    <h5 class="topic-title">{{__("billing information")}}</h5>
                    <p class="address">{{ $order->address }}</p>
                </div>
            </div>

            <ul class="order-summery-list">
                <li class="single-order-summery border-bottom">
                    <div class="content border-bottom ex">
                                    <span class="subject text-deep">
                                        {{__("product")}}
                                    </span>
                        <span class="object text-deep">
                                        {{__("subtotal")}}
                                    </span>
                    </div>

                    <ul class="internal-order-summery-list">
                        @foreach(json_decode($order->order_details) ?? [] as $product)
                            <li class="internal-single-order-summery">
                                            <span class="internal-subject">{!! render_image_markup_by_attachment_id($product->options?->image) !!} {{ $product?->name }}
                                                @if(!empty($product->options?->color_name))
                                                    : {{ __("Size") }} : {{ $product->options?->color_name }} ,
                                                @endif

                                                @if(!empty($product->options?->size_name))
                                                    {{ __("Color") }} : {{ $product->options?->size_name }}
                                                @endif

                                                @if(!empty($product->options?->attributes))
                                                    ,
                                                    @foreach($product->options?->attributes ?? [] as $key => $value)
                                                        {{ $key }} : {{ $value }} @if($loop->last) , @endif
                                                    @endforeach
                                                @endif

                                                <i class="las la-times icon"></i>
                                                <span class="times text-deep">{{ $product->qty }}</span>
                                            </span>
                                <span class="internal-object">
                                                {{ amount_with_currency_symbol(($product->price * $product->qty) ?? 0) }}
                                            </span>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="single-order-summery border-bottom">
                    <div class="content">
                                    <span class="subject text-deep">
                                        {{__("subtotal")}}
                                    </span>
                        <span class="object text-deep">
                                        {{ amount_with_currency_symbol($order_meta->subtotal ?? 0) }}
                                    </span>
                    </div>
                </li>
                <li class="single-order-summery">
                    <div class="content">
                                    <span class="subject text-deep">
                                        {{__("coupon discount")}}
                                    </span>
                        <span class="object">
                                        -{{ amount_with_currency_symbol($order_meta->coupon_amount ?? 0) }}
                                    </span>
                    </div>
                </li>
                <li class="single-order-summery">
                    <div class="content">
                                    <span class="subject text-deep">
                                        {{__("tax")}}
                                    </span>
                        <span class="object">
                                        +{{ amount_with_currency_symbol($order_meta->product_tax) }}
                                    </span>
                    </div>
                </li>
                <li class="single-order-summery border-bottom">
                    <div class="content">
                                    <span class="subject text-deep">
                                        {{__("shipping cost")}}
                                    </span>
                        <span class="object">
                                        +{{ amount_with_currency_symbol($order_meta->shipping_cost) }}
                                    </span>
                    </div>
                </li>
                <li class="single-order-summery border-bottom">
                    <div class="content total">
                                    <span class="subject text-deep color-main">
                                        {{__("total")}}
                                    </span>
                        <span class="object text-deep color-main">
                                        {{ amount_with_currency_symbol($order_meta->total) }}
                                    </span>
                    </div>
                </li>
                <li class="single-order-summery">
                    <div class="content total">
                                    <span class="subject text-deep">
                                        {{__("payment method")}}
                                    </span>
                        <span class="object">
                                        {{ $order->payment_gateway ?? __("Cash on delivery") }}
                                    </span>
                    </div>
                </li>
            </ul>
            <!-- Order summery end     -->
        </div>
        <!-- Order summery end -->
@endsection
