
<div class="parent" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="sliderArea eventSlider heroImgBg hero-overly" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
        <div class="global-slick-init slider-inner-margin arrowStyleThree" data-rtl="{{ get_slider_language_deriection() }}" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left-long"></i></div>'
             data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right-long"></i></div>'>

   @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $ti)
        @php
            $original_title = $ti ?? '';
            $explode = explode(' ',$original_title);
            $after_explode_title = $explode;

            $first_two_words = array_slice($after_explode_title,0,2);
            $number_three_word = array_slice($after_explode_title,2,1);
            $last_four_words = array_slice($after_explode_title,-4,4);

            $merge = array_merge($first_two_words,$number_three_word,$last_four_words);
            $animation_word = array_diff($after_explode_title,$merge);

            $implode_animation_word = implode(' ',$animation_word);

            $description = $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '';
            $button_text = $data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? '';
            $button_url = $data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '';
            $right_image = $data['repeater_data']['repeater_right_image_'.get_user_lang()][$key] ?? '';
        @endphp

                <div class="single-slider">
                    <div class="container">
                        <div class="row justify-content-between align-items-end">
                            <div class="col-xxl-6 col-xl-6 col-lg-6">
                                <div class="heroCaption heroPadding">
                                    <h1 class="tittle textEffect" data-animation="ladeInUp">
                                        {{ $original_title}}
                                    </h1>
                                    <p class="pera" data-animation="ladeInUp" data-delay="0.3s">{!! $description !!}</p>

                                    <div class="btn-wrapper d-flex align-items-center flex-wrap">
                                        <a href="{{ $button_url }}" class="cmn-btn0 hero-btn mr-15 mb-15 " data-animation="ladeInLeft" data-delay="0.5s">{{ $button_text }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-xl-6 col-lg-6">
                                <div class="hero-man d-none d-lg-block f-right running" >
                                    {!! render_image_markup_by_attachment_id($right_image,'','','ladeInUp','0.2s') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

         @endforeach
            </div>
        </div>

</div>
