
<div class="single-shop-details-wrapper padding-left-50">
    @if($campaign_product !== null && $campaign_product->status !== 'draft' && ($product?->inventory?->stock_count > 0))
        <div class="campaign_countdown_wrapper mb-5">
            <h3 class="text-capitalize text-start mb-3">{{$campaign_name}}</h3>

            @if($is_expired)
                <div class="global-timer"></div>
            @else
                <div class="text-capitalize alert alert-warning">
                    <h5>{{__('The Campaign is over or not yet started')}}</h5>
                </div>
            @endif
        </div>
    @endif

    <div class="name_badge">
        <h2 class="tittle"> {{$product->getTranslation('name',get_user_lang())}}</h2>
    </div>

    {!! render_product_star_rating_markup_with_count($product) !!}

    <div class="productPrice">
        <span class="regularPrice flash-prices"
              data-main-price="{{ $sale_price }}"
              data-currency-symbol="{{ site_currency_symbol() }}"
              id="{{ $quickView ? "quick-view-price" : "price" }}"> {{amount_with_currency_symbol($sale_price)}}</span>
        <span class="offerPrice">{{$deleted_price != null ? amount_with_currency_symbol($deleted_price) : ''}}</span>
    </div>

    <div class="value-input-area">
        @if($productSizes->count() > 0 && !empty(current(current($productSizes))))
            <div class="value-input-area single-input-list mt-4 size_list  {{ $quickView ? "quick-view-value-input-area" : "" }}">
                    <span class="input-title fw-500 color-heading">
                        <strong class="color-light"> {{ __('Size:') }} </strong>
                        <input readonly class="form--input value-size" name="size" type="text" value="">
                        <input type="hidden" id="selected_size">
                    </span>
                <ul class="size-lists select-list {{ $quickView ? "quick-view-size-lists" : "" }}" data-type="Size">
                    @foreach($productSizes as $product_size)
                        @if(!empty($product_size))
                            <li class="list"
                                data-value="{{ optional($product_size)->id }}"
                                data-display-value="{{ optional($product_size)->getTranslation('name',get_user_lang()) }}"
                            > {{ optional($product_size)->size_code }} </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif

        @if($productColors->count() > 0 && current(current($productColors)))
            <div
                class="value-input-area single-input-list mt-4 color_list  {{ $quickView ? "quick-view-value-input-area" : "" }}">
                    <span class="input-title fw-500 color-heading">
                        <strong class="color-light"> {{ __('Color:') }} </strong>
                        <input readonly class="form--input value-size" name="color" type="text" value="">
                        <input type="hidden" id="selected_color">
                    </span>
                <ul class="size-lists color-list {{ $quickView ? "quick-view-size-lists" : "" }}" data-type="Color">
                    @foreach($productColors as $product_color)
                        @if(!empty($product_color))
                            <li style="background-color: {{$product_color->color_code}}"
                                data-value="{{ optional($product_color)->id }}"
                                data-display-value="{{ optional($product_color)->name }}"
                            ></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif


        @foreach($available_attributes as $attribute => $options)
            <div class="value-input-area single-input-list mt-4 attribute_options_list  {{ $quickView ? "quick-view-value-input-area" : "" }}">
                        <span class="input-title fw-500 color-heading input-list">
                            <strong class="color-light"> {{ $attribute }} </strong>
                            <input readonly class="form--input value-size" type="text" value="">
                            <input type="hidden" id="selected_attribute_option" name="selected_attribute_option">
                        </span>
                <ul class="size-lists {{ $quickView ? "quick-view-size-lists" : "" }}" data-type="{{ $attribute }}">
                    @foreach($options as $option)
                        <li class="list"
                            data-value="{{ $option }}"
                            data-display-value="{{ $option }}"
                        > {{ $option }} </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <div class="quantity-area mt-4">
        <div class="quantity-flex mb-30">
            <div class="countWrap styleTwo mr-10">
              <div class="numberCount product-quantity">
                  <div class="value-button minus decrease {{ $quickView ? "quick-view-" : "" }}substract substract"><i class="las la-minus"></i></div>
                   <input class="{{ $quickView ? "quick-view-" : "" }}quantity-input quantity-input qty_" type="number"
                       id="{{ $quickView ? "quick-view-" : "" }}quantity" name="quantity" value="1">
                  <div class="value-button plus increase {{ $quickView ? "quick-view-" : "" }}plus plus"><i class="las la-plus"></i></div>
              </div>
           </div>
            @php
                if ($product?->inventory?->stock_count > 0)
                    {
                        $text_color = 'text-success';
                        $text = __('Stock Available');
                    } else {
                        $text_color = 'text-danger';
                        $text = __('Stock Out');
                    }
            @endphp

            <span class="stock-available color-stock stock-available color-stock {{$text_color}}" id="{{ $quickView ? "quick_view_" : "" }}item_left">
                {{$text}}
                <span class="color-paragraph"> ({{$product?->inventory?->stock_count}}) </span>
            </span>
        </div>

        <div class="quantity-btn mt-4">
            <ul class="cartList">
                <li class="listItem">

               <a href="javascript:void(0)" class="{{ $quickView ? "quick_view_add_to_wishlist" : "add_to_wishlist_single_page" }} btn-wishlist share-icon">
                    <span class="icon">
                       <button type="button" class="items"><i class="lar la-heart icon"></i></button>
                    </span>
                    </a>
              </li>

                <li class="listItem">
                    <a href="javascript:void(0)"
                       class="items btn-wishlist share-icon fw-500 {{ $quickView ? "quick-view-" : "" }}compare-btn"
                       data-product_id="{{$product->id}}"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="{{__('Add to Compare')}}">
                    <span class="icon">
                        <i class="las la-retweet icon"></i>
                    </span>
                    </a>
                </li>

                <li class="listItem"> <button type="button" class="items {{ $quickView ? "quick_view_add_to_cart" : "add_to_cart_single_page" }}
                    cmn-btn cmn-btn-bg-heading cart-loading"><i class="flaticon-shopping-cart icon"></i></button>
                </li>

                <li class="listItem">
                    <div class="btn-wrapper">
                        <a href="#!" data-product_id="{{ $product->id }}" class="{{ $quickView ? "quick_view_but_now" : "buy_now_single_page" }} buy_now_single_pagecmn-btn cmn-btn4 cart-loading"> {{__('Buy Now')}} </a>
                    </div>
                </li>

            </ul>

        </div>

    </div>
    <div class="wishlist-compare mt-4">
        <div class="wishlist-share social_share_parent">
            @php
                $product_primary_image = get_attachment_image_by_id($product->image_id);
                $product_primary_image = $product_primary_image ? $product_primary_image['img_url'] : '';
            @endphp
            <ul class="social_share_wrapper_item d-inline-flex">
                {!! single_post_share(route('tenant.shop.product.details',$product->slug), $product->name, $product_primary_image) !!}
            </ul>
        </div>
    </div>
    <div class="shop-details-stock shop-border-top pt-4 mt-4">
        <ul class="stock-category">
            <li class="category-list">
                <span class="list-item fw-600">
                    @php
                        $category_route_con = !empty($product?->category?->slug) ? route('tenant.shop.category.products', ['category' ,$product?->category?->slug]) : '';
                        $subcategory_route_con = !empty($product?->category?->slug) ? route('tenant.shop.category.products', ['subcategory' ,$product?->subCategory?->slug]) : '';
                    @endphp

                    <a href="{{$category_route_con}}">{{$product?->category?->getTranslation('name',get_user_lang())}}</a>
                    |
                    <a href="{{$subcategory_route_con}}">{{$product?->subCategory?->getTranslation('name',get_user_lang())}}</a>
                    |
                    @foreach($product->childCategory ?? [] as $child_category)
                        @php
                            $child_catroute_con = !empty($child_category?->slug) ? route('tenant.shop.category.products', ['child-category' ,$child_category?->slug]) : '';
                        @endphp
                        <a href="{{$child_catroute_con}}"> {{$child_category->getTranslation('name',get_user_lang())}} </a>

                        @if(!$loop->last)
                            ,
                        @endif
                    @endforeach
                </span>
            </li>
            @if($product->uom != null)
                <li class="category-list">
                    <span> {{__('Unit:')}} </span>
                    <a class="list-item fw-600" href="javascript:void(0)">
                        <span>{{$product?->uom?->quantity}}</span>
                        <span>{{$product?->uom?->uom_details?->name}}</span>
                    </a>
                </li>
            @endif
            <li class="category-list">
                <span> {{__('SKU:')}} </span>
                <a class="list-item fw-600" href="javascript:void(0)"> {{$product?->inventory?->sku}} </a>
            </li>
        </ul>
        <div class="delivery-options delivery-parent mt-4">
            @if($product->product_delivery_option != null)
                @foreach($product->product_delivery_option as $option)
                    <div class="delivery-item d-flex">
                        <div class="icon">
                            <i class="{{ $option->icon }}"></i>
                        </div>
                        <div class="content">
                            <h6 class="title">{{ $option->getTranslation('title',get_user_lang()) }}</h6>
                            <p>{{ $option->getTranslation('sub_title',get_user_lang()) }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
