
<div class="sliderArea heroImgBg" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
    <div class="slider-active">
        <div class="single-slider heroPadding d-flex align-items-center">
            <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
                <div class="row justify-content-between align-items-center">
                    <div class="col-xxl-6 col-xl-6 col-lg-6 ">
                        <div class="heroCaption">

                            <div class="section-tittle mb-40">
                                {!! get_modified_title_tenant($data['title']) !!}
                            </div>
                            <p class="pera" data-animation="fadeInUp" data-delay="0.3s">{{$data['description']}}</p>

                            <div class="btn-wrapper d-flex align-items-center flex-wrap">
                                <a href="{{$data['button_url']}}" class="cmn-btn2 hero-btn mr-15 mb-15 " data-animation="fadeInLeft" data-delay="0.4s">{{$data['button_text']}} <i class="fas fa-heart icon ZoomTwo"></i></a>
                                <a href="{{$data['button_right_url']}}" class="offerDate" .
                                   data-animation="fadeInRight" data-delay="0.4s">{{$data['button_right_text']}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-6 col-lg-6">
                        <div class="hero-man d-none d-lg-block f-right" >
                            @if(!empty($data['header_mask_image']))
                            {!! render_image_markup_by_attachment_id($data['right_image'],'tilt-effect maskImg','','','fadeInRight','0.2s') !!}
                            @endif
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
    <!-- Shape 03-->

    <div class="shapeHero shapeHero3" data-animation="fadeInDown" data-delay="0.7s">
        @php
            if(!empty($data['hand_image'])) {
                echo '<img src="'.global_asset('assets/tenant/frontend/themes/img/hero/donation-heroShpe3.png').'" alt="" class="running">';
            }
        @endphp
    </div>

</div>
