@php
    $button_text = $data['button_text'];
@endphp
<section class="popularProduct">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row mb-40">
            <div class="col-xl-6 col-lg-12 col-md-10 col-sm-10">
                <div class="section-tittle mb-0">
                    <h2 class="title">{{ $data['title'] }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">

                <div class="global-slick-init slider-inner-margin arrowStyleFour" data-rtl="{{ get_slider_language_deriection() }}" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 991,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 2}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>

                    @foreach($data['products'] as $product)
                        @php
                            $data = get_product_dynamic_price($product);
                            $campaign_name = $data['campaign_name'];
                            $regular_price = $data['regular_price'];
                            $sale_price = $data['sale_price'];
                            $discount = $data['discount'];
                        @endphp
                        <div class="singleProduct">
                            <div class="productImg imgEffect2">
                                <a href="{{route('tenant.shop.product.details',$product->slug)}}">
                                    {!! render_image_markup_by_attachment_id($product->image_id) !!}
                                </a>
                                <div class="sticky-wrap">
                                    @if(!empty($product->badge))
                                      <span class="sticky stickyStye ">{{$product?->badge?->name}}</span>
                                    @endif

                                    @if($product->inventory?->stock_count < 1)
                                         <span class="sticky stickyStye outStock">{{__('Out of stock')}}</span>
                                    @endif
                                </div>
                                @include('product::frontend.option-feature')

                            </div>
                            <div class="productCap">
                                <h5><a href="{{route('tenant.shop.product.details', $product->slug)}}" class="title">{{Str::words($product->name, 4)}} </a></h5>

                                {!! render_product_star_rating_markup_with_count($product) !!}

                                @if($product->inventory?->stock_count > 0)
                                 <span class="quintity avilable">{{__('In Stock')}} <span class="quintityNumber">({{$product->inventory?->stock_count}})</span> </span>
                                @else
                                    <span class="quintity text-danger">{{__('Stock Out')}} <span class="quintityNumber">({{$product->inventory?->stock_count}})</span> </span>
                                @endif
                                <div class="productPrice">
                                    <strong class="regularPrice">{{amount_with_currency_symbol($sale_price)}}</strong>
                                    <span class="offerPrice">{{$regular_price != null ? amount_with_currency_symbol( $regular_price) : ''}}</span>
                                </div>
                                <div class="btn-wrapper" >
                                    @if(count($product->inventoryDetail) > 0 )
                                        <a href="{{route('tenant.shop.product.details',$product->slug)}}" class="cmn-btn-outline3 w-100">{{__('View')}}</a>
                                    @else
                                        <a href="#!" data-product_id="{{ $product->id }}" class="cmn-btn-outline3 w-100 add-to-buy-now-btn">{{$button_text}}</a>
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
