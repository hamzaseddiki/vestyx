<section class="newspaper_entertainment_area  padding-top-50 padding-bottom-50">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="newspaper_section__title border__bottom text-left title_flex">
            <h4 class="title">{{$data['title']}}</h4>
            <div class="append-entertainment"></div>
        </div>
        @php
            $lang_rtl_con = get_user_lang_direction() == 1 ? 'true' : 'false';
        @endphp
        <div class="row g-4 mt-1">
            <div class="col-lg-12">
                <div class="global-slick-init recent-slider nav-style-one slider-inner-margin" data-rtl="{{$lang_rtl_con}}" data-appendArrows=".append-entertainment" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 576,"settings": {"slidesToShow": 1}}]'>


                    @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                        <div class="newspaper_entertainment__item">
                            <div class="newspaper_entertainment">
                                <div class="newspaper_entertainment__thumb">
                                    <a href="{{$data['repeater_data']['repater_title_url_'.get_user_lang()][$key] ?? ''}}">
                                        {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '') !!}
                                    </a>
                                </div>
                                <div class="newspaper_entertainment__contents">
                                    <div class="newspaper_entertainment__tag mt-3">
                                        <a href="{{$data['repeater_data']['repater_button_url_'.get_user_lang()][$key] ?? ''}}" class="newspaper_entertainment__tag__item"> {{$data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? ''}}</a>
                                    </div>
                                    <h4 class="newspaper_entertainment__title mt-2">
                                        <a href="{{$data['repeater_data']['repater_title_url_'.get_user_lang()][$key] ?? ''}}">{{$title ?? ''}}</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
