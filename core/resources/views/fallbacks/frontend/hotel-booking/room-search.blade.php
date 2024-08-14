@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('title')
    {!! __('Search/Rooms') !!}
@endsection

@section('page-title')
    {!! __('Search/Rooms') !!}
@endsection

@section('meta-data')
@endsection

@section('style')
    <style>
        .singleBlog-details .blogCaption .cartTop {
            margin-bottom: 16px;
        }
        .singleBlog-details .blogCaption .cartTop .listItmes {
            display: inline-block;
            margin-right: 10px;
            font-size: 16px;
            font-weight: 300;
        }
        .singleBlog-details .blogCaption .cartTop .listItmes .icon {
            color: var(--peragraph-color);
            margin-right: 10px;
        }

        .sidebar-searchBar {
            cursor: pointer;
            font-size: 20px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            height: 35px;
            width: 35px;
            color: #fff;
            background-color: var(--main-color-one);
            border: unset;
            font-weight: 500;
            -webkit-transition: 300ms;
            transition: 300ms;
            border-radius: 5px;
            margin-left: auto;
        }
    </style>
@endsection

@section('content')
    @php
        $lang_slug =  $user_lang ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="responsive-overlay"></div>
    <section class="hotel-list-area section-bg-2 pat-100 pab-100">
        <div class="container">
            <div class="banner-location bg-white radius-10">
                <form action="{{route('tenant.frontend.search_room')}}" method="post">
                    @csrf
                <div class="banner-location-flex">
                    <div class="banner-location-single">
                        <div class="banner-location-single-flex">
                            <div class="banner-location-single-icon">
                                <i class="las la-calendar"></i>
                            </div>
                            <div class="banner-location-single-contents">
                                <span class="banner-location-single-contents-subtitle">   {{__('Check In')}}</span>
                                <div class="banner-location-single-contents-dropdown custom-select">
                                    <input name="check_in" value="{{@$search_data['check_in']?$search_data['check_in']:today()}}" class="select-style-two form--control test-picker select-style-two lg" type="text" placeholder="Check in">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="banner-location-single">
                        <div class="banner-location-single-flex">
                            <div class="banner-location-single-icon">
                                <i class="las la-calendar"></i>
                            </div>
                            <div class="banner-location-single-contents">

                                <span class="banner-location-single-contents-subtitle"> {{__('Check Out')}}  </span>
                                <div class="banner-location-single-contents-dropdown custom-select">
                                    <input name="check_out" value="{{@$search_data['check_out']?$search_data['check_out']:today()}}" class="select-style-two form--control test-picker select-style-two lg" type="text" placeholder="Check out">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="banner-location-single">
                        <div class="banner-location-single-flex">
                            <div class="banner-location-single-icon">
                                <i class="las la-user-friends"></i>
                            </div>

                            <div class="banner-location-single-contents">
                                <span class="banner-location-single-contents-subtitle"> {{__('Person')}}  </span>
                                <div class="banner-location-single-contents-dropdown custom-select">
                                    <input  type="number" name="person" value="{{@$search_data['person'] ? @$search_data['person'] : 1}}" id="Person" class="form-control"
                                            value="1" pattern="[0-9]">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banner-location-single">
                        <div class="banner-location-single-flex">
                            <div class="banner-location-single-icon">
                                <i class="las la-user-friends"></i>
                            </div>
                            <div class="banner-location-single-contents">
                                <span class="banner-location-single-contents-subtitle"> {{__('Children')}} </span>
                                <div class="banner-location-single-contents-dropdown custom-select">
                                    <input  type="number" name="children" value="{{@$search_data['children'] ? @$search_data['children'] : 0}}" id="children" class="form-control"
                                            value="0" pattern="[0-9]">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banner-location-single-search">
                        <div class="search-suggestions-wrapper">
                            <button type="submit" class="search-click-icon"><i class="las la-search"></i></button>
                        </div>
                        <div class="search-suggestion-overlay"></div>
                    </div>

                </div>
                </form>
            </div>
            <div class="shop-contents-wrapper mt-5">
                <div class="shop-icon">
                    <div class="shop-icon-sidebar">
                        <i class="las la-bars"></i>
                    </div>
                </div>
                <div class="shop-sidebar-content mt-4">
                    <div class="shop-close-content">
                        <div class="shop-close-content-icon"> <i class="las la-times"></i> </div>
                        <div class="single-shop-left bg-white radius-10">
                            <div class="single-shop-left-title open">
                                <h5 class="title">  {{__('Prices')}} </h5>

                                <div class="single-shop-left-inner mt-4">
                                    <form  action="{{route('tenant.frontend.search_room')}}" class="price-range-slider" method="post" data-start-min="0" data-start-max="10000" data-min="0" data-max="10000" data-step="5">
                                        @csrf
                                        <div class="ui-range-slider"></div>
                                        <div class="ui-range-slider-footer">
                                            <div class="ui-range-values">
                                                <span class="ui-price-title"> {{__('Prices')}}: </span>
                                                <div class="ui-range-value-min">{{ @site_currency_symbol() }}<span class="min_price">100</span>
                                                    <input type="hidden" name="min_price" id="min_price" value="100">
                                                </div>-
                                                <div class="ui-range-value-max">{{ @site_currency_symbol() }}<span class="max_price">9950</span>
                                                    <input type="hidden" name="max_price" id="max_price" value="9950">
                                                </div>
                                            </div>
                                            <button type="submit" class="float-end sidebar-searchBar"><i class="las la-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="single-shop-left bg-white radius-10 mt-4">
                            <div class="single-shop-left-title open">
                                <h5 class="title"> {{__('Amenities')}} </h5>
                                <form id="amenityForm" action="{{route('tenant.frontend.search_room')}}" class="price-range-slider" method="post" data-start-min="0" data-start-max="10000" data-min="0" data-max="10000" data-step="5">
                                    @csrf
                                <div class="single-shop-left-inner margin-top-15">
                                    @foreach($all_amenities as $item)
                                    <div class="form-check mb-3">
                                       <li><input class="form-check-input" @if($item->id == $amenity_id) checked @endif name="amenity_id" id="amenity_id" type="radio" value="{{$item->id}} " onclick="amenityClick({{$item->id}})"></li>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            {{ $item->getTranslation('name',$lang_slug)}}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </form>
                            </div>
                        </div>
                        <div class="single-shop-left bg-white radius-10 mt-4">
                            <div class="single-shop-left-title open">
                                <h5 class="title"> {{__('Filter By Rating')}}</h5>
                                <div class="single-shop-left-inner">
                                    <form id="thisForm" action="{{route('tenant.frontend.search_room')}}" method="post">
                                        @csrf
                                    <input type="hidden" id="ratting" name="ratting">
                                    <ul class="single-shop-left-filter-list active-list mt-3">
                                        <li class="item @if($ratting == 5 || '') active @elseif(!$ratting) active @endif" onclick="setStarValue(5)">
                                            <a href="javascript:void(0)" onclick="setStarValue(5)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)" onclick="setStarValue(5)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)" onclick="setStarValue(5)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)" onclick="setStarValue(5)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)" onclick="setStarValue(5)"> <i class="las la-star"></i> </a>
                                        </li>
                                        <li class="item {{$ratting == 4 ? 'active' : ''}}" onclick="setStarValue(4)">
                                            <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                        </li>
                                        <li class="item {{$ratting == 3 ? 'active' : ''}}" onclick="setStarValue(3)">
                                            <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                        </li>
                                        <li class="item {{$ratting == 2 ? 'active' : ''}}" onclick="setStarValue(2)">
                                            <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                        </li>
                                        <li class="item {{$ratting == 1 ? 'active' : ''}}" onclick="setStarValue(1)">
                                            <a href="javascript:void(0)"> <i class="las la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                            <a href="javascript:void(0)"> <i class="lar la-star"></i> </a>
                                        </li>
                                    </ul>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shop-grid-contents">
                    <div id="tab-grid" class="tab-content-item active mt-4">
                        <div class="row gy-4">
                            @foreach($all_rooms as $item)
                                <div class="col-md-6">
                                    <div class="hotel-view bg-white radius-20">
                                        <a href="{{route('tenant.frontend.room_details',$item->slug)}}" class="hotel-view-thumb hotel-view-grid-thumb bg-image" {!! render_background_image_markup_by_attachment_id($item->room_image[0]->image_id) !!}>
                                        </a>
                                        <div class="hotel-view-contents">
                                            <div class="hotel-view-contents-header">
                                                @if($item->averageRating !=0 )
                                                <span class="hotel-view-contents-review"> {{ round($item->averageRating,1) }} <span class="hotel-view-contents-review-count"> @if($item->reviews_count != 0) ({{$item->reviews_count}}) @endif </span> </span>
                                                @endif
                                                <h3 class="hotel-view-contents-title"> <a href="{{route('tenant.frontend.room_details',$item->slug)}}"> {{ $item->getTranslation('name',$lang_slug)}} </a> </h3>
                                                    <div class="hotel-view-contents-location mt-2">
                                                        <span class="hotel-view-contents-location-icon"> <i class="fa-solid fa-hotel"></i> </span>
                                                        <span class="hotel-view-contents-location-para"> {{$item->room_types->hotel? $item->room_types->hotel->getTranslation('name',$lang_slug) : ''}}  </span>
                                                    </div>
                                                    <div class="hotel-view-contents-location mt-2">
                                                    <span class="hotel-view-contents-location-icon"> <i class="las la-map-marker-alt"></i> </span>
                                                    <span class="hotel-view-contents-location-para">  {{@$item->room_types->state->name}}, {{@$item->room_types->country->name}}  </span>
                                                </div>
                                            </div>
                                            <div class="hotel-view-contents-middle">
                                                <div class="hotel-view-contents-flex">
                                                    @foreach($item->room_types->room_type_amenities as $data)
                                                        <div class="hotel-view-contents-icon myTooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $data->getTranslation('name',$lang_slug)}}">
                                                            <i class=" {{ $data->icon }}"></i>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="hotel-view-contents-bottom">
                                                <div class="hotel-view-contents-bottom-flex">

                                                    <div class="hotel-view-contents-bottom-contents">
                                                        <h4 class="hotel-view-contents-bottom-title"> {{ @amount_with_currency_symbol($item->room_types->base_charge) }} <sub>/ {{__('Night')}}</sub> </h4>
                                                        <p class="hotel-view-contents-bottom-para">( {{$item->room_types->max_adult}} {{__('Person')}},  {{$item->room_types->max_child}} {{__('Children')}} ,  {{$item->room_types->no_bedroom}} {{__('BedRoom')}}) </p>
                                                    </div>
                                                    <div class="btn-wrapper">
                                                        <a href="{{route('tenant.frontend.room_details',$item->slug)}}" class="cmn-btn btn-bg-1 btn-small">{{__('Reserve Now')}}  </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($all_rooms->isEmpty())
                        <div class="text-center text-danger mt-5">
                            <h3 class="text-danger"> {{__('No Matching Room to show')}}</h3>
                        </div>
                        @endif
                    </div><br>
                    {!! $all_rooms->withQueryString()->links('pagination::bootstrap-5') !!}
                </div>
            </div>
            </div>
    </section>

@endsection

@section('scripts')
    @yield("custom-ajax-scripts")
    <script>

        function setStarValue(value) {
            document.getElementById('ratting').value = value;
            document.getElementById('thisForm').submit();
            $search_value = value;
        }

        // Get today's date
        var today = new Date();
        var fourteenDaysFromToday = new Date(today);
        fourteenDaysFromToday.setDate(today.getDate() + 14);

        flatpickr(".test-picker", {
            enableTime: false,
            minDate: "today",
            maxDate: fourteenDaysFromToday,
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        // if amenity clicked form will be submited
        function amenityClick(value)
        {
            document.getElementById('amenity_id').value = value;
            document.getElementById('amenityForm').submit();
        }
    </script>

@endsection
