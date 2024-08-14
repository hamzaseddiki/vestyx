
<div class="headerCart-body ShowWishList ">
    <!-- Mobile device closed icon -->
    <span class="closed closedWishList "><i class="fa-solid fa-xmark"></i></span>
    <!-- Card Contents -->
    <div class="cartProduct-wrapper">
        <div class="small-tittle mb-40">
            <h2 class="tittle">{{__('Wish List items')}}</h2>
        </div>
        @php
            $cart = \Gloudemans\Shoppingcart\Facades\Cart::instance("wishlist")->content();
            $subtotal = \Gloudemans\Shoppingcart\Facades\Cart::instance("wishlist")->subtotal();
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
                        <span class="price">{{amount_with_currency_symbol($cart_item->price)}}</span>

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

                    <div class="deleteProduct top_right_close_table_wishlist" data-product_hash_id="{{$cart_item->rowId}}">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </figcaption>
            </figure>
        @empty
            <figure class="singleCartproduct">
                <p class="text-center">{{__('No Item in Wishlist')}}</p>
            </figure>
        @endforelse


        @if($cart->count() != 0)
            <div class="totalPriceCart">
                <p class="subtotal">{{__('subtotal')}}</p> <span class="total">{{site_currency_symbol().$subtotal}}</span>
            </div>
        @endif

        <div class="btn-wrapper">
            <a href="{{url('shop')}}" class="cmn-btn-outline3 w-100 mb-10">{{__('Continue Shopping')}}</a>
            <a href="{{route('tenant.shop.wishlist.page')}}" class="cmn-btn4 w-100">{{__('View Wishlist')}}</a>
        </div>
    </div>
</div>
