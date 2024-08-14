
<div class="sliderArea heroImgBg" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
    <div class="global-slick-init slider-inner-margin arrowStyleThree" data-rtl="{{ get_slider_language_deriection() }}" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left-long"></i></div>'
         data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right-long"></i></div>'>

 @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $ti)
    @php
        $original_title = $ti ?? '';
        $exploded = explode(' ',$original_title);
        $exploed_data = $exploded;
        $last_two_words = array_slice($exploded,-2,2) ?? '';
        $first_words = array_diff($exploed_data,$last_two_words) ?? '';

        $description = $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '';
        $button_text = $data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? '';
        $button_url = $data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '';

        $bottom_right_text = $data['repeater_data']['repeater_bottom_right_text_'.get_user_lang()][$key] ?? '';
        $bottom_right_url = $data['repeater_data']['repeater_bottom_right_url_'.get_user_lang()][$key] ?? '';

        $right_image = $data['repeater_data']['repeater_right_image_'.get_user_lang()][$key] ?? '';
    @endphp
    <div class="slider-active">
        <div class="single-slider heroPadding d-flex align-items-center">
            <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
                <div class="row justify-content-between align-items-center">
                    <div class="col-xxl-6 col-xl-6 col-lg-6 ">
                        <div class="heroCaption">


                            <h1 class="tittle" data-animation="fadeInUp" data-delay="0.1s">
                                {{ implode(' ', $first_words) }}
                                <span class="lineBrack"></span>
                                <span class="tittleColor slideEffect"> {{ implode(' ',$last_two_words) }}</span>
                            </h1>
                            <p class="pera" data-animation="fadeInUp" data-delay="0.3s">{{$description}}</p>

                            <div class="btn-wrapper d-flex align-items-center flex-wrap">
                                <a href="{{$button_url}}" class="cmn-btn2 hero-btn mr-15 mb-15 " data-animation="fadeInLeft" data-delay="0.4s">{{$button_text}} <i class="fas fa-heart icon ZoomTwo"></i></a>
                                <a href="{{$bottom_right_url}}" class="offerDate" .
                                   data-animation="fadeInRight" data-delay="0.4s">{{$bottom_right_text}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-6 col-lg-6">
                        <div class="hero-man d-none d-lg-block f-right" >

                            {!! render_image_markup_by_attachment_id($right_image,'tilt-effect maskImg','','','fadeInRight','0.2s') !!}
                            <!-- Shape 01-->
                            <div class=" shapeHero shapeHero1" data-animation="fadeInLeft" data-delay="0.8s">
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/hero/donation-heroShape1.png')}}" alt="images" class="bouncingAnimation">
                            </div>
                            <!-- Shape 02-->
                            <div class=" shapeHero shapeHero2" data-animation="fadeInLeft" data-delay="0.8s">
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/hero/donation-heroShape2.png')}}" alt="images" class="bouncingAnimation ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  @endforeach
    </div>
    <!-- Shape 03-->
    <div class="shapeHero shapeHero3 " data-animation="fadeInDown" data-delay="0.7s">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/hero/donation-heroShpe3.png')}}" alt="" class="running">
    </div>
</div>
