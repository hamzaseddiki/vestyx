
<section class="relatedProduct mt-5 pb-100">
    <div class="container">
        <div class="row mb-40">
            <div class="col-xl-6 col-lg-12 col-md-10 col-sm-10">
                <div class="section-tittle mb-0">
                    <h2>{{__('Related Products')}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="global-slick-init slider-inner-margin arrowStyleFour" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 991,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 2}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>

                    @foreach($related_products as $product)
                        @php
                            if ($loop->odd) {
                                    $delay = 1;
                                    $class = 'fadeInDown';
                                }
                            else {
                                $delay = 2;
                                $class = 'fadeInUp';
                            }

                            $img_data = get_attachment_image_by_id($product->image_id, 'grid');
                            $img = !empty($img_data) ? $img_data['img_url'] : '';
                            $alt = !empty($img_data) ? $img_data['img_alt'] : '';

                            $discount = null;
                            if ($product->price)
                                {
                                    $discount = round(($product->sale_price / $product->price)*100, 2);
                                }
                        @endphp


                        <div class="singleProduct">
                        <div class="productImg imgEffect2">
                            <a href="{{route('tenant.shop.product.details', $product->slug)}}">
                                <img src="{{$img}}" alt="{{$alt}}">
                            </a>
                            <!-- sticker -->



                                <div class="sticky-wrap">
                                    @if($product->inventory?->stock_count < 1)
                                        <span class="sticky stickyStye outStock">{{__('Out of stock')}}</span>
                                    @endif

                                    @if(!empty($product->badge))
                                        <span class="sticky stickyStye"> {{$product?->badge?->name}} </span>
                                    @endif
                                </div>

                            @include('product::frontend.option-feature')
                        </div>
                        <div class="productCap">
                            <h5>
                                <a href="{{route('tenant.shop.product.details', $product->slug)}}" class="title">{{Str::words($product->getTranslation('name',get_user_lang()), 4)}}</a>
                            </h5>

                            {!! render_product_star_rating_markup_with_count($product) !!}

                            @if($product->inventory?->stock_count > 0)
                                <span class="quintity avilable">{{__('In Stock')}} <span class="quintityNumber">({{$product->inventory?->stock_count}})</span> </span>
                            @else
                                <span class="quintity text-danger">{{__('Stock Out')}} <span class="quintityNumber">({{$product->inventory?->stock_count}})</span> </span>
                            @endif

                            <div class="productPrice">
                                {!! product_prices($product, 'color-two') !!}
                            </div>
                            <div class="btn-wrapper">

                                @if(count($product->inventoryDetail) > 0 )
                                    <a href="{{route('tenant.shop.product.details',$product->slug)}}" class="cmn-btn-outline3 w-100">{{__('View')}}</a>
                                @else
                                    <a href="#!" data-product_id="{{ $product->id }}" class="cmn-btn-outline3 w-100 add-to-buy-now-btn">{{__('Buy Now')}}</a>
                                @endif

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
