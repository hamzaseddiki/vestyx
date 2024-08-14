<div class="agency_banner" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container" >
        <div class="row align-items-center justify-content-center flex-column-reverse flex-lg-row">
            <div class="sliderArea" >
                <div class="global-slick-init slider-inner-margin arrowStyleThree" data-rtl="{{ get_slider_language_deriection() }}" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left-long"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right-long"></i></div>'>

                    @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $ti)
                        @php
                            $original_title = $ti ?? '';
                            $description = $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '';
                            $button_text = $data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? '';
                            $button_url = $data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '';
                            $right_image = $data['repeater_data']['repeater_right_image_'.get_user_lang()][$key] ?? '';
                        @endphp
                        <div class="slider-active">
                            <div class="single-slider d-flex align-items-center" >
                                <div class="col-lg-6">
                                    <div class="agency_banner__content__wrapper">
                                        <div class="agency_banner__single">
                                            <div class="agency_banner__single__content">
                                                <h2 class="agency_banner__single__content__title fw-600">
                                                    <span class="agency_banner_title_shape">
                                                     <img src="{{global_asset('assets/tenant/frontend/themes/img/agency/banner/agency_banner_title_shape.svg')}}" alt="">

                                                         {!! get_modified_title_agency($original_title) !!}
                                                </h2>
                                                <p class="agency_banner__single__content__para mt-3"> {{$description}}</p>
                                                <div class="btn-wrapper">
                                                    <a href="{{$button_url}}" class="cmn-agency-btn cmn-agency-btn-bg-1 radius-0 mt-4 mt-lg-5"> {{$button_text}} </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="agency_banner__right">
                                        <div class="agency_banner_main__thumb thumb_shape">
                                            {!! render_image_markup_by_attachment_id($right_image) !!}
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
</div>
