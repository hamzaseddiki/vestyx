    <!-- Hero Area S t a r t -->
 <div class="sliderArea plr sectionBg1" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="global-slick-init slider-inner-margin arrowStyleThree" data-rtl="{{ get_slider_language_deriection() }}" data-infinite="false" data-arrows="false" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left-long"></i></div>'
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
            <div class="single-slider d-flex align-items-center">
                <div class="container-fluid ">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-xxl-6 col-xl-7 col-lg-7 ">
                            <div class="heroCaption">

                                <h1 class="tittle" data-animation="fadeInUp" data-delay="0.1s">{{$original_title}}</h1>

                                <p class="pera" data-animation="fadeInUp" data-delay="0.3s">{{$description}}</p>

                                <div class="btn-wrapper btn-rtl">
                                    <a href="{{$button_url}}" class="cmn-btn2 hero-btn mr-15 mb-10 wow fadeInLeft" data-wow-delay="0.2s">{{$button_text}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-5 col-xl-5 col-lg-5">
                            <div class="hero-man d-none d-lg-block f-right" >
                                {!! render_image_markup_by_attachment_id($right_image, 'tilt-effect lazy', '','fadeInRight','0.2s') !!}
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
    <!-- End-of Hero  -->
