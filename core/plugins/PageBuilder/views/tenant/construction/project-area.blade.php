
<section class="construction_project_area padding-top-100 padding-bottom-50">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="construction_sectionTitle__two text-left title_flex">
            <h2 class="title">{{$data['title']}}</h2>
            <div class="append_project"></div>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-lg-12">
                @php
                    $lang_direction = get_user_lang_direction() == 1 ? 'true' : 'false';
                @endphp

                <div class="global-slick-init blog-slider dot-style-one slider-inner-margin" data-rtl="{{$lang_direction}}" data-appendArrows=".append_project" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 3}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}}]'>

                    @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                        <div class="slick-slider-item">
                            <div class="construction_project radius-10">
                                <div class="construction_project__thumb">
                                    <a href="{{$data['repeater_data']['repeater_url_'.get_user_lang()][$key] ?? ''}}">

                                        {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '') !!}
                                    </a>
                                </div>
                                <div class="construction_project__contents">
                                    <div class="construction_project__contents__flex">
                                        <div class="construction_project__contents__details">
                                            <h4 class="construction_project__title"><a href="javascript:void(0)">{{$title ?? '' }}</a></h4>
                                            <p class="construction_project__para"><i class="fa-solid fa-location-dot"></i> {{$data['repeater_data']['repeater_location_'.get_user_lang()][$key] ?? ''}}</p>
                                        </div>
                                        <a href="{{$data['repeater_data']['repeater_url_'.get_user_lang()][$key] ?? ''}}" class="construction_project__contents__icon">
                                            <i class="fa-solid fa-arrow-right-long"></i>
                                        </a>
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
