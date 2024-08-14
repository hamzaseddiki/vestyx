@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('page-title')
    {{ $room->getTranslation('name',$user_lang)}}
@endsection

@section('title')
    {{ $room->getTranslation('name',$user_lang)}}
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
    </style>
@endsection

@section('content')
    @php
        $lang_slug = $user_lang ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <section class="hotel-details-area section-bg-2 pat-100 pab-100">
        <div class="container">
            <x-error-msg/>
            <x-flash-msg/>
            <div class="row g-4">
                <div class="col-xl-8 col-lg-7">
                    <div class="details-left-wrapper">
                        <div class="details-contents bg-white radius-10">
                            <div class="details-contents-header">
                                <div class="details-contents-thumb details-contents-main-thumb bg-image" {!! render_background_image_markup_by_attachment_id($room->room_image[0]->image_id) !!}>
                                </div>
                                <div class="details-contents-header-flex">
                                    <div class="details-contents-header-thumb" {!! render_background_image_markup_by_attachment_id($room->room_image[0]->image_id) !!}>
                                    </div>
                                    <div class="details-contents-header-thumb" {!! render_background_image_markup_by_attachment_id($room->room_image[0]->image_id) !!}>
                                    </div>
                                </div>
                            </div>
                            <div class="hotel-view-contents">
                                <div class="hotel-view-contents-header">
                                    @if($room->averageRating !=0 )
                                        <span class="hotel-view-contents-review"> {{ round($room->averageRating,1) }} <span class="hotel-view-contents-review-count"> @if($room->reviews_count != 0) ({{$room->reviews_count}}) @endif </span> </span>
                                    @endif
                                    <h3 class="hotel-view-contents-title"> {{ $room->getTranslation('name',$lang_slug)}} </h3>
                                    <div class="hotel-view-contents-location mt-2">
                                        <span class="hotel-view-contents-location-icon"> <i class="fa-solid fa-hotel"></i> </span>
                                        <span class="hotel-view-contents-location-para"> {{$room->room_types->hotel? $room->room_types->hotel->getTranslation('name',$lang_slug) : ''}}  </span>
                                    </div>
                                    <div class="hotel-view-contents-location mt-2">
                                        <span class="hotel-view-contents-location-icon"> <i class="las la-map-marker-alt"></i> </span>
                                        <span class="hotel-view-contents-location-para"> {{$room->room_types->state->name}}, {{$room->room_types->country->name}}  </span>
                                    </div>
                                </div>
                                <div class="hotel-view-contents-middle">
                                    <div class="hotel-view-contents-flex">
                                        @foreach($room->room_types->room_type_amenities as $data)
                                            <div class="hotel-view-contents-icon myTooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $data->getTranslation('name',$lang_slug)}}">
                                                <i class="{{ $data->icon }}"></i>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="hotel-view-contents-bottom">
                                    <div class="hotel-view-contents-bottom-flex">
                                        <div class="hotel-view-contents-bottom-contents">
                                            <h4 class="hotel-view-contents-bottom-title"> {{ @amount_with_currency_symbol($room->room_types->base_charge) }} <sub>/Night</sub> </h4>
                                            <p class="hotel-view-contents-bottom-para"> ( {{$room->room_types->max_adult}} {{__('Person')}},  {{$room->room_types->max_child}} {{__('Children')}} ,  {{$room->room_types->no_bedroom}} {{__('BedRoom')}}, {{$room->room_types->no_living_room}} {{__('LivingRoom')}}) </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="details-contents-tab">
                                <ul class="tabs details-tab details-tab-border">
                                    <li class="active" data-tab="about">   {{__('About')}} </li>
                                    <li data-tab="reviews"> {{__('Reviews')}}</li>
                                </ul>
                                <div id="about" class="tab-content-item active">
                                    <div class="about-tab-contents">
                                        <p class="about-tab-contents-para"> {{ $room->getTranslation('description',$lang_slug)}} </p>
                                    </div>
                                </div>
                                <div id="reviews" class="tab-content-item">
                                    <div class="review-tab-contents">
                                        @foreach($hotel_room_reviews as $item)
                                            <div class="review-tab-contents-single">
                                                <div class="rating-wrap">
                                                    <div class="ratings">
                                                        <span class="hide-rating"></span>
                                                        <span class="show-rating"></span>
                                                    </div>
                                                    <p>
                                                        <span class="total-ratings">
                                                            <button class="btn btn-info btn-sm"> {{round($item->ratting ,1)}}</button>
                                                        </span></p>
                                                </div>
                                                <p class="about-review-para mt-3"> {{$item->description}} </p>
                                                <div class="review-tab-contents-author mt-4">
                                                    <h4 class="review-tab-contents-author-name"> {{\App\Models\User::find($item->user_id)->name}} </h4>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if ($reviewAvailability)
                                    <div class="col-xl-12 col-lg-5 mt-5">
                                        <div class="faq-question faq-question-border radius-10 sticky-top">
                                            <h3 class="faq-question-title">   {{__('Add Review')}}</h3>
                                            <div class="faq-question-form custom-form mat-20">
                                                <form method="post" action="{{route('tenant.frontend.hotel-reviews')}}" class="row g-3">
                                                    @csrf
                                                    <div class="col-lg-6">
                                                        <div class="single-input">
                                                            <x-fields.select name="comfort" title="{{__('Comfort')}}">
                                                                <option value="1" >1</option>
                                                                <option value="2" >2</option>
                                                                <option value="3" >3</option>
                                                                <option value="4"  >4</option>
                                                                <option value="5" selected>5</option>
                                                            </x-fields.select>
                                                            <input type="hidden" name="hotel_id" value="{{$room->room_types->hotel->id}}" class="form--control radius-10" placeholder="5.0">
                                                            <input type="hidden" name="room_id" value="{{$room->id}}" class="form--control radius-10" placeholder="5.0">
                                                            <input type="hidden" name="user_id" value="{{Auth()->guard('web')->user()->id}}" class="form--control radius-10" placeholder="5.0">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <x-fields.select name="cleanliness" title="{{__('Cleanliness')}}">
                                                                <option value="1" >1</option>
                                                                <option value="2" >2</option>
                                                                <option value="3" >3</option>
                                                                <option value="4" selected>4</option>
                                                                <option value="5" >5</option>
                                                        </x-fields.select>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <x-fields.select name="staff" title="{{__('Staff')}}">
                                                            <option value="1" >1</option>
                                                            <option value="2" >2</option>
                                                            <option value="3" >3</option>
                                                            <option value="4" >4</option>
                                                            <option value="5" selected>5</option>
                                                        </x-fields.select>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <x-fields.select name="facilities" title="{{__('Facilities')}}">
                                                            <option value="1" >1</option>
                                                            <option value="2" >2</option>
                                                            <option value="3" >3</option>
                                                            <option value="4" selected>4</option>
                                                            <option value="5" >5</option>
                                                        </x-fields.select>
                                                    </div>

                                                     <div class="col-lg-12">
                                                        <div class="single-input">
                                                            <label class="label-title">  {{__('description')}} </label>
                                                            <textarea name="description" class="form--control form-message radius-10" placeholder="Type Your description..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <button type="submit" class="btn btn-primary mb-3"> {{__('Submit Review')}} </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    <div class="related-room-area padding-top-50">
                        <div class="text-center text-danger mt-3">
                            <h3> {{__('Related Rooms')}}</h3>
                        </div>
                        <div class="related-room-wrapper mt-4">
                            @if($related_rooms->isEmpty())
                                <div class="text-center text-danger mt-5">
                                    <h3 class="text-danger"> {{__('No Related Room to show')}}</h3>
                                </div>
                            @endif
                            @foreach($related_rooms as $item)
                                <div class="related-room-item">
                                    <div class="hotel-view bg-white radius-20 mt-2">
                                        <div class="hotel-view-flex">
                                            <a href="{{route('tenant.frontend.room_details',$room->slug)}}" class="hotel-view-thumb hotel-view-list-thumb related-rooms bg-image" {!! render_background_image_markup_by_attachment_id($room->room_image[0]->image_id) !!}>
                                            </a>
                                            <div class="hotel-view-contents">
                                                <h3 class="hotel-view-contents-title"> {{ $room->getTranslation('name',$lang_slug)}} </h3>
                                                <div class="hotel-view-contents-header">
                                                    <div class="hotel-view-contents-header-flex d-flex flex-wrap gap-3 align-items-center justify-content-between">
                                                        @if($room->averageRating !=0 )
                                                            <span class="hotel-view-contents-review"> {{ round($room->averageRating,1) }} <span class="hotel-view-contents-review-count"> @if($room->reviews_count != 0) ({{$room->reviews_count}}) @endif </span> </span>
                                                        @endif
                                                        <h4 class="hotel-view-contents-bottom-title"> {{ @amount_with_currency_symbol($room->room_types->base_charge) }} <sub>/{{__('Night')}} </sub> </h4>
                                                        <div class="btn-wrapper">
                                                            <a href="{{route('tenant.frontend.room_details',$room->slug)}}" class="cmn-btn btn-bg-1 btn-small">  {{__('Details')}}  </a>
                                                        </div>
                                                    </div>
                                                    <div class="hotel-view-contents-location mt-2">
                                                        <span class="hotel-view-contents-location-icon"> <i class="fa-solid fa-hotel"></i> </span>
                                                        <span class="hotel-view-contents-location-para"> {{$item->room_types->hotel? $item->room_types->hotel->getTranslation('name',$lang_slug) : ''}}  </span>
                                                    </div>
                                                    <div class="hotel-view-contents-location mt-2">
                                                        <span class="hotel-view-contents-location-icon"> <i class="las la-map-marker-alt"></i> </span>
                                                        <span class="hotel-view-contents-location-para"> {{$room->room_types->state->name}}, {{$room->room_types->country->name}}  </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="sticky-top">
                        <div class="hotel-details-widget hotel-details-widget-padding widget bg-white radius-10">
                            <form id="checkoutForm" action="{{route('tenant.frontend.checkout')}}" method="post">
                                @csrf
                                <div class="details-sidebar">
                                     <div class="details-sidebar-dropdown custom-form">
                                        <div class="single-input">
                                            <label class="details-sidebar-dropdown-title"> {{__('Check In')}} </label>
                                            <input name="from_date"  value="{{@$search_room_data['check_in']}}" class="form--control  test-picker" type="text" placeholder="Check in">
                                            <input name="room_id" class="form--control" value="{{@$room->id}}" type="hidden" >
                                            <input name="room_type_id" class="form--control" value="{{@$room->room_types->id}}" type="hidden">
                                            <input name="hotel_id" class="form--control" value="{{@$room->room_types->hotel_id}}" type="hidden">
                                            <input name="room_type_person" class="form--control" value="{{@$room->room_types->max_adult}}" type="hidden">
                                            <input name="room_type_children" class="form--control" value="{{@$room->room_types->max_child}}" type="hidden">
                                        </div>
                                        <div class="single-input mt-3">
                                            <label class="details-sidebar-dropdown-title"> {{__('Check Out')}} </label>
                                            <input name="to_date" value="{{@$search_room_data['check_out']}}" class="form--control test-picker" type="text" placeholder="Check out">
                                        </div>
                                </div>
                                <div class="details-sidebar-quantity pt-4">
                                    <div class="details-sidebar-quantity-flex">
                                        <div class="details-sidebar-quantity-item">
                                            <h6 class="details-sidebar-quantity-title"> {{__('Person')}} </h6>
                                            <div class="details-sidebar-quantity-field">
                                                <span class="minus"><i class="las la-minus"></i></span><input class="quantity-input" type="number" name="person" value="{{@$search_room_data['person']?$search_room_data['person']:1}}"><span class="plus"><i class="las la-plus"></i></span>
                                            </div>
                                        </div>

                                        <div class="details-sidebar-quantity-item">
                                            <h6 class="details-sidebar-quantity-title">{{__('Children')}}  </h6>
                                            <div class="details-sidebar-quantity-field">
                                                <span class="minus"><i class="las la-minus"></i></span><input class="quantity-input" type="number" name="children" value="{{@$search_room_data['children']?$search_room_data['children']:0}}"><span class="plus"><i class="las la-plus"></i></span>
                                            </div>
                                        </div>
                                        <div class="details-sidebar-quantity-item">
                                            <h6 class="details-sidebar-quantity-title">  {{__('Room')}}</h6>
                                            <div class="details-sidebar-quantity-field">
                                                <span class="minus"><i class="las la-minus"></i></span><input name="room" class="quantity-input" type="number" value="1"><span class="plus"><i class="las la-plus"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-wrapper mt-4">
                                    <button type="submit" class="cmn-btn btn-bg-1 btn-small">  {{__('Go to Checkout')}}  </button>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    @yield("custom-ajax-scripts")
    <script>
        $(document).on('submit', '#checkoutForm', function (e) {
            e.preventDefault();
            var form = $(this);
            var formID = form.attr('id');
            var formSelector = document.getElementById(formID);
            var formData = new FormData(formSelector);

            $.ajax({
                url: "{{route('tenant.frontend.checkout')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}",
                },
                processData: false,
                contentType: false,
                data:formData,
                success: function (data) {
                    if (data.warning_msg)
                    {
                        CustomSweetAlertTwo.warning(data.warning_msg)
                    }
                    else{
                        window.location.href = data.redirect_url;
                    }

                }
            })
        });

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
    </script>

@endsection
