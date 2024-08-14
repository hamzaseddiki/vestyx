@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Order Details')}}
@endsection
@section('style')
    <x-media-upload.css/>
        <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/module-fix-style.css')}}">
@endsection
@section('title')
    {{__('Order Details')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('Order Details')}}</h4>
                        <x-link-with-popover url="{{route(route_prefix().'admin.product.order.manage.all')}}"
                                             class="info">{{__('All Orders')}}</x-link-with-popover>

                        @php
                            $order_meta = json_decode($order->payment_meta);
                        @endphp

                        <!-- Order status start-->
                        <div class="order-status-wrap order-details-page">
                            <table class="order-status-inner">
                                <tbody>
                                <tr class="complete">
                                    <td>
                                        <span class="order-number"> {{__("Order")}} #{{ $order->id }}</span>
                                        <span class="price">{{ amount_with_currency_symbol($order->total_amount) }}</span>
                                    </td>
                                    <td>
                                        <span class="">{{ $order->created_at?->format("M d, Y") }}</span>
                                        <span class="">{{ $order->created_at?->format("H:ia") }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-wrapper">
                                            <span class="order-btn-custom status">{{__('Order Status').': '.$order->status }}</span>
                                            <span class="order-btn-custom status">{{__('Payment Status').': '. $order->payment_status }}</span>
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
                                    <span class="">{{ $order->created_at?->format("M d, Y") }}</span>
                                    <span class="">{{ $order->created_at?->format("H:ia") }}</span>
                                </div>

                                <div class="address">
                                    <h5 class="topic-title">{{__("billing information")}}</h5>
                                    <p>
                                        <span>{{__('Country:')}}</span>
                                        <span>{{$order->getCountry?->name}}</span>
                                    </p>
                                    <p>
                                        <span>{{__('State:')}}</span>
                                        <span>{{$order->getState?->name}}</span>
                                    </p>
                                    <p>
                                        <span>{{__('City:')}}</span>
                                        <span>{{$order->city}}</span>
                                        <span>{{$order->zipcode}}</span>
                                    </p>
                                    <p>
                                        <span>{{__('Zip Code:')}}</span>
                                        <span>{{$order->zipcode}}</span>
                                    </p>
                                    <p class="address">
                                        <span>{{__('Address:')}}</span>
                                        <span>{{ $order->address }}</span>
                                    </p>
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

                                                @if(!empty($product->options->attributes))
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
                                        -{{ amount_with_currency_symbol($order->coupon_discounted ?? 0) }}
                                    </span>
                                    </div>
                                </li>

                                <li class="single-order-summery">
                                    <div class="content">
                                    <span class="subject text-deep">
                                        {{__("tax")}}
                                    </span>

                                        <span class="object"> <small class="text-primary">+({{ $order_meta->product_tax }}%)</small> {{  amount_with_currency_symbol(($order_meta->subtotal - $order->coupon_discounted) / 100 * $order_meta->product_tax) }}</span>

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
                                        {{ str_replace('_',' ',$order->payment_gateway) ?? __("Cash on delivery") }}
                                    </span>
                                    </div>
                                </li>

                                @if($order->payment_gateway != 'bank_transfer')
                                <li class="single-order-summery">
                                    <div class="content total">
                                    <span class="subject text-deep">
                                        {{__("Transaction ID")}}
                                    </span>
                                        <span class="object">
                                        {{ $order->transaction_id }}
                                    </span>
                                    </div>
                                </li>
                                 @endif

                                @if($order->payment_gateway == 'bank_transfer')
                                    <li class="single-order-summery">
                                        <div class="content total">
                                    <span class="subject text-deep">
                                        {{__("Bank Payment Document")}}
                                    </span>
                                     <span class="object">
                                      <a href="{{ url('assets/uploads/attachment/'.$order->manual_payment_attachment) }}" target="_blank">{{__('Click to see')}}</a>
                                    </span>
                                        </div>
                                    </li>
                                @endif

                            </ul>
                            <!-- Order summery end     -->
                        </div>
                        <!-- Order summery end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>
@endsection
