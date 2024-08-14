<section class="wedding_need_area position-relative wedding-section-bg padding-top-100 padding-bottom-100" data-padding-top="{{$data['padding_top']}}"
         data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="gradient_bg">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="wedding_shape">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/need/wedding_hearts.png')}}" alt="">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/need/wedding_flower1.png')}}" alt="">
    </div>
    <div class="container">
        <div class="wedding_sectionTitle">
            <h2 class="title">{{$data['title']}}</h2>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-lg-12">
                <div class="global-slick-init need-slider dot-style-one slider-inner-margin" data-rtl="{{get_slider_language_deriection()}}" data-arrows="false" data-infinite="false" data-dots="true" data-slidesToShow="4" data-swipeToSlide="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 3}},{"breakpoint": 768,"settings": {"slidesToShow": 2}},{"breakpoint": 425, "settings": {"slidesToShow": 1} }]'>

                    @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                        @php
                            $repeater_button_text = $data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? '';
                            $repeater_button_url = $data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '';
                            $repeater_image = $data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '';
                        @endphp
                        <div class="slick_slider_item">
                            <div class="wedding__need center-text radius-10">
                                <div class="wedding__need__thumb">
                                    <a href="{{$repeater_button_url}}">
                                      {!! render_image_markup_by_attachment_id($repeater_image) !!}
                                    </a>
                                </div>
                                <div class="wedding__need__contents mt-3">
                                    <h4 class="wedding__need__title"><a href="{{$repeater_button_url}}">{{$title}}</a></h4>
                                    <div class="btn-wrapper mt-3">
                                        <a href="{{$repeater_button_url}}" class="wedding__need__learnMore">{{$repeater_button_text}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
