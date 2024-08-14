
<div id="tab-grid2" class="tab-content-item active">
    <div class="row">
        @foreach($products as $product)
            @php
                $data = get_product_dynamic_price($product);
                $campaign_name = $data['campaign_name'];
                $regular_price = $data['regular_price'];
                $sale_price = $data['sale_price'];
                $discount = $data['discount'];
            @endphp

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-6">
                <div class="singleProduct mb-24">
                    <div class="productImg imgEffect2">
                        <a href="{{route('tenant.shop.product.details', $product->slug)}}">
                            {!! render_image_markup_by_attachment_id($product->image_id, '', 'grid') !!}
                        </a>
                        <!-- sticker -->
                        <div class="sticky-wrap">
                            @if($discount != null)
                                <span class="sticky stickyStye outStock"> {{$discount}}% {{__('off')}} </span>
                            @endif

                            @if(!empty($product->badge))
                                <span class="sticky stickyStye"> {{$product?->badge?->getTranslation('name',get_user_lang())}} </span>
                            @endif
                        </div>

                        @include('product::frontend.shop.partials.product-options')
                    </div>
                    <div class="productCap">
                        <h5 class="global-card-contents-title text-capitalize">
                            <a href="{{route('tenant.shop.product.details', $product->slug)}}"> {{Str::words($product->getTranslation('name',get_user_lang()), 4)}} </a>
                        </h5>

                        {!! render_product_star_rating_markup_with_count($product) !!}

                        @if($product->inventory?->stock_count > 0)
                            <span class="quintity avilable">{{__('In Stock')}} <span class="quintityNumber">({{$product->inventory?->stock_count}})</span> </span>
                        @else
                            <span class="quintity text-danger">{{__('Stock Out')}} <span class="quintityNumber">({{$product->inventory?->stock_count}})</span> </span>
                        @endif

                        <div class="productPrice">
                            <strong class="regularPrice">{{$regular_price != null ? amount_with_currency_symbol( $sale_price) : ''}}</strong>
                            <span class="offerPrice">{{amount_with_currency_symbol($regular_price)}}</span>

                        </div>

                        <div class="btn-wrapper mb-15">

                            @if(count($product->inventoryDetail) > 0 )
                                <a href="{{route('tenant.shop.product.details',$product->slug)}}" class="cmn-btn-outline3 w-100">{{__('View')}}</a>
                            @else
                                <a href="#!" data-product_id="{{ $product->id }}" class="cmn-btn-outline3 w-100 add-to-buy-now-btn">{{__('Buy Now')}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

            <div class="pagination mt-60">
                <ul class="pagination-list">
                    @if(count($links) > 1)
                        @foreach($links as $link)
                            <li>
                                <a data-page="{{ $loop->iteration }}" href="{{ $link }}" class="page-number {{ $loop->iteration === $current_page ? "current" : ""}}">{{ $loop->iteration }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
    </div>
</div>
