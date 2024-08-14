
<section class="agency_testimonial_area padding-top-50 padding-bottom-50">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="agency_section__title">
            {!! get_modified_title_agency_two($data['title']) !!}
        </div>

        <div class="row g-4 mt-4 justify-content-between align-items-center">
            @php
                $rtl_condition = get_user_lang_direction() == 1 ? 'true' : 'false';
            @endphp
            <div class="col-xl-6 col-lg-6">
                <div class="global-slick-init testimonial_thumb_slider nav-style-one slider-inner-margin" data-rtl="{{$rtl_condition}}" data-asNavFor=".testimonial_contents_slider" data-infinite="true" data-arrows="false" data-fade="true" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>'>
                    @foreach($data['repeater_data']['repeater_image_'.get_user_lang()] ?? [] as $key=> $image)
                        <div class="agency_testimonial">
                            <div class="agency_testimonial__left thumb_border__bottom">
                                <div class="agency_testimonial__thumb">
                                    {!! render_image_markup_by_attachment_id($image ?? null) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <div class="col-xl-5 col-lg-6">
                <div class="global-slick-init testimonial_contents_slider nav-style-one slider-inner-margin" data-rtl="{{$rtl_condition}}" data-asNavFor=".testimonial_thumb_slider" data-appendArrows=".append-testimonial" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>'>

                    @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                        <div class="agency_testimonial__right">
                            <div class="agency_testimonial__contents">
                                <h4 class="agency_testimonial__contents__title">{{$title ?? ''}}</h4>
                                <div class="agency_testimonial__contents__comments mt-5">
                                    <span class="agency_testimonial__contents__comments__quote"><i class="las la-quote-left"></i></span>
                                    <p class="agency_testimonial__contents__para">{{$data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? ''}}</p>
                                </div>
                                <div class="agency_testimonial__rating mt-4">
                                    <div class="agency_testimonial__rating__flex d-flex gap-1">
                                        <span class="agency_testimonial__rating__icon"><i class="las la-star"></i></span>
                                        <span class="agency_testimonial__rating__icon"><i class="las la-star"></i></span>
                                        <span class="agency_testimonial__rating__icon"><i class="las la-star"></i></span>
                                        <span class="agency_testimonial__rating__icon"><i class="las la-star"></i></span>
                                        <span class="agency_testimonial__rating__icon"><i class="las la-star"></i></span>
                                    </div>
                                    <div class="agency_testimonial__rating__author mt-2">
                                        <h5 class="agency_testimonial__rating__author__title">{{$data['repeater_data']['repeater_name_'.get_user_lang()][$key] ?? ''}}</h5>
                                        <p class="agency_testimonial__rating__author__subtitle mt-1">{{$data['repeater_data']['repeater_designation_'.get_user_lang()][$key] ?? ''}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="append-testimonial mt-4 mt-xl-5"></div>
            </div>
        </div>
    </div>
</section>
