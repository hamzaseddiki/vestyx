
<figure class="headerCart-body ShowCart ">
    <button class="closed closedCart"><i class="fa-solid fa-xmark"></i></button>

    <figure class="cartProduct-wrapper mb-50">
        <div class="small-tittle mb-40">
            <h2 class="tittle">{{__('Cart List items')}}</h2>
        </div>
        @php
            $cart = \Gloudemans\Shoppingcart\Facades\Cart::instance("default")->content();
            $cart_subtotal = \Gloudemans\Shoppingcart\Facades\Cart::instance("default")->subtotal();
       @endphp
        @forelse($cart as $cart_item)
            @php
                $product = \Modules\Product\Entities\Product::findOrFail($cart_item->id);
                $details_route = route('tenant.shop.product.details',$product->slug);
            @endphp
            <figure class="singleCartproduct">
                <div class="proImg">
                    <a href="{{$details_route}}">
                        {!! render_image_markup_by_attachment_id($cart_item?->options?->image) !!}
                    </a>
                </div>
                <figcaption class="proDiscription">
                    <div class="cap">
                        <a href="{{$details_route}}" class="title">{{Str::words($cart_item->name, 5)}} </a>
                        <span class="price"> {{amount_with_currency_symbol($cart_item->price)}}</span>

                        <div class="cartCap">
                            <span class="name-subtitle d-block mt-2">
                                        @if($cart_item?->options?->color_name)
                                    {{__('Color:')}} {{$cart_item?->options?->color_name}},
                                @endif

                                @if($cart_item?->options?->size_name)
                                    {{__('Size:')}} {{$cart_item?->options?->size_name}}
                                @endif

                                @if($cart_item?->options?->attributes)
                                    <br>
                                    @foreach($cart_item?->options?->attributes as $key => $attribute)
                                        {{$key.':'}} {{$attribute}}{{!$loop->last ? ',' : ''}}
                                    @endforeach
                                @endif
                                     </span>
                        </div>
                    </div>
                    <div class="deleteProduct top_right_close_table_cart" data-product_hash_id="{{$cart_item->rowId}}">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </figcaption>
            </figure>
        @empty
            <figure class="singleCartproduct">
                <p class="text-center">{{__('No Item in Cart')}}</p>
            </figure>
        @endforelse



        <!-- price -->
        @if($cart->count() != 0)
        <div class="totalPriceCart">
            <p class="subtotal">{{__('subtotal')}}</p> <span class="total">{{site_currency_symbol().$cart_subtotal}} </span>
        </div>
        @endif
        <div class="btn-wrapper">
            <a href="{{url('shop')}}" class="cmn-btn-outline3 w-100 mb-10">{{__('Continue Shopping')}}</a>
            <a href="{{route('tenant.shop.cart')}}" class="cmn-btn4 w-100 mb-10">{{__('View Shopping Cart')}}</a>
            <a href="{{route('tenant.shop.checkout')}}" class="cmn-btn4 w-100">{{__('Go to Checkout')}}</a>
        </div>
    </figure>
</div>
