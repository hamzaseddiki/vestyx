

<div class="sliderArea sectionBg1" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="slider-active">
        @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
            <div class="single-slider">
                <div class="container" >
                    <div class="row justify-content-between align-items-end">
                        <div class="col-xxl-6 col-xl-6 col-lg-6 ">
                            <div class="heroCaption heroPadding">
                                <h1 class="title" data-animation="fadeInUp" data-delay="0.1s">
                                    {{$title ?? ''}}
                                </h1>
                                <p class="pera" data-animation="fadeInUp" data-delay="0.3s">
                                   {{$data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '' }}
                                </p>

                                <div class="btn-wrapper">
                                    <a href=" {{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '' }}" class="cmn-btn2 hero-btn" data-animation="fadeInLeft" data-delay="0.4s">
                                        {{$data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? '' }}
                                    </a>
                                </div>

                                <!-- Shape 01-->
                                <div class="shapeHero shapeHero1 " data-animation="fadeInDown" data-delay="0.7s">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/hero/eCommerce-heroShpe1.png')}}" alt="image" class="heartbeat3">
                                </div>

                                <!-- Shape 04-->
                                <div class="shapeHero shapeHero4 " data-animation="fadeInDown" data-delay="0.7s">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/hero/eCommerce-heroShpe4.png')}}" alt="image" class="heartbeat3">
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-xl-6 col-lg-6 position-relative">
                            <div class="hero-man d-none d-lg-block f-right" >
                                {!! render_image_markup_by_attachment_id( $data['repeater_data']['repeater_right_image_'.get_user_lang()][$key] ?? '', '','','fadeInRight' ,'0.2s') !!}
                            </div>

                            <!-- Shape -->
                            <div class="shapeHero shapeHero2 " data-animation="fadeInDown" data-delay="0.7s">
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/hero/eCommerce-heroShpe2.png')}}" alt="image" class="routedOne">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <div class="shapeHero shapeHero3 wow fadeInLeft" data-wow-delay="0.5s">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/hero/eCommerce-heroShpe3.png')}}" alt="image" class="routedOne">
    </div>
</div>
