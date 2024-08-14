<div class="row justify-content-between mb-24">

        <div class="col-xl-4  col-lg-5 col-md-6 ">
            <div class="searchBox-wrapper searchBox-wrapper-sidebar">
                <!-- Search input Box -->
                <form action="{{route('tenant.shop.search')}}" class="search-box">
                    <div class="input-form">
                        <input type="text" class="keyup-input" name="search" placeholder="{{ __('Search') }}">
                        <!-- icon -->
                        <button class="icon" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

      {{--Top Tag Filters--}}
        <div class="col-xl-4 col-lg-3 col-md-5">
            <div class="selectItems ">
                <div class="selectder-filter-contents click-hide-filter d-inline-flex d-none">
                    <span> {{__('Selected Filter')}} : </span>
                    <div class="selected-clear-items">
                        <ul class="selected-flex-list" id="_porduct_fitler_item">

                        </ul>
                    </div>
                        <a class="click-hide-parent mx-3" data-filter="all" href="javascript:void(0)"> {{__('Clear All')}} </a>
                </div>
            </div>
        </div>
      {{--Top Tag Filters--}}

    <div class="col-xl-4 col-lg-3 col-md-5">
        <div class="selectItems f-right">
            @include('product::frontend.shop.partials.filter-partials.shop-filters')
        </div>
    </div>

</div>

<div class="row">
    <div class="col-xxl-4 col-xl-3 col-lg-4 col-md-5">
           <div class="categoryListing mb-50">
            <div class="simplePresentCart3 mb-24">
                <div class="single_category">
                    <div class="small-tittle mb-10 mr-10">
                        <h2 class="tittle">{{__('Category')}}</h2>
                    </div>
                    <div class="categoryOption">
                        <ul class="category-lists active-list">
                            @foreach($categories as $category)
                              <li class='singleOption list d-flex justify-content-between category-filter' data-slug="{{$category->slug}}" data-value="{{ $category->name }}">
                                  <a class="item ad-values" href='#0' data-value="{{ $category->name }}" data-slug="{{$category->slug}}">{{$category->name}} </a>
                                  <span> {{$category->product_categories_count}} </span>
                              </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="simplePresentCart3 mb-24">
                <div class="shop-left-title open">
                    <h5 class="title title-borders fw-500"> {{__('Filter Prices')}} </h5>
                    <div class="shop-left-list mt-4">
                        <form class="price-range-slider" method="post" data-start-min="0" data-start-max="10000" data-min="0" data-max="10000" data-step="5">
                            <div class="ui-range-slider"></div>
                            <div class="ui-range-slider-footer">
                                <div class="ui-range-values">
                                    <span class="ui-price-title"> {{__('Price')}} : </span>
                                    <div class="ui-range-value-min">{{site_currency_symbol()}}<span class="min_price">0</span>
                                        <input type="hidden" value="0">
                                    </div> -
                                    <div class="ui-range-value-max">{{site_currency_symbol()}}<span class="max_price">10000</span>
                                        <input type="hidden" value="10000">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="simplePresentCart3 mb-24">
                <div class="single-shop-left">
                    <div class="shop-left-title open">
                        <h5 class="title title-borders fw-500"> {{__('Sizes')}} </h5>
                        <div class="shop-left-list margin-top-15">
                            <ul class="size-lists active-list">
                                @foreach($sizes as $size)
                                    <li class="list size-filter" data-slug="{{$size->id}}" data-value="{{ $size->name }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ucfirst($size->name)}}">
                                        <a class="radius-5" href="javascript:void(0)"> {{$size->size_code}} </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
               </div>

            <div class="simplePresentCart3 mb-24">
                <div class="shop-left-title open">
                    <h5 class="title title-borders fw-500"> {{__('Color')}} </h5>
                    <div class="shop-left-list margin-top-15">
                        <ul class="color-lists active-list">
                            @foreach($colors as $color)
                                <li class="list color-filter" data-value="{{$color->name}}" data-slug="{{$color->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ucfirst($color->name)}}">
                                    @php
                                        if (strtolower($color->color_code) == '#fff' || strtolower($color->color_code) == '#ffffff')
                                            {
                                                $border_class = 'border: 1px solid #d8d8d8';
                                            }
                                    @endphp
                                    <a class="radius-5" style="background-color: {{$color->color_code}};{{$border_class ?? ''}}" href="javascript:void(0)"> </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
           </div>

            <div class="simplePresentCart3 mb-24">
                <div class="shop-left-title open">
                    <h5 class="title title-borders fw-500"> {{__('Rating')}} </h5>
                    <div class="shop-left-list">
                        <ul class="filter-lists active-list mt-3">
                            <li data-slug="5" class="rating-filter list">
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                            </li>
                            <li data-slug="4" class="rating-filter list">
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                            </li>
                            <li data-slug="3" class="rating-filter list">
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                            </li>
                            <li data-slug="2" class="rating-filter list">
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                            </li>
                            <li data-slug="1" class="rating-filter list">
                                <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                            </li>
                        </ul>
                    </div>
                </div>
               </div>

           <div class="simplePresentCart3 mb-24">
                   <!-- Tag -->
                   <div class="tagArea">
                       <div class="small-tittle mb-40">
                           <h3 class="tittle">{{__('Tags')}}</h3>
                       </div>

                       <ul class="tag-lists active-list selectTag">
                           @foreach($tags as $tag)
                               <li class="list listItem tag-filter" data-slug="{{$tag->tag_name}}">
                                   <a class="radius-0 text-capitalize" href="javascript:void(0)"> {{$tag->tag_name}} </a>
                               </li>
                           @endforeach
                       </ul>

                   </div>
               </div>

        </div>
    </div>



