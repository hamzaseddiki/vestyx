<section class="agency_work_area padding-top-100 padding-bottom-50">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_top']}}">
        <div class="agency_section__title text-left title_flex">
            {!! get_modified_title_agency_two($data['title']) !!}
            <div class="append-work"></div>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-lg-12">
                @php
                    $rtl_condition = get_user_lang_direction() == 1 ? 'true' : 'false';
                @endphp
                <div class="global-slick-init recent-slider nav-style-one slider-inner-margin" data-rtl="{{$rtl_condition}}" data-appendArrows=".append-work" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 3}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 1}}]'>

                    @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                        <div class="agency_work__item">
                            <div class="agency_work">
                                <div class="agency_work__thumb">
                                    <a href="{{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? ''}}">
                                        {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '') !!}
                                    </a>
                                </div>
                                <div class="agency_work__contents mt-4">
                                    <h4 class="agency_work__title"><a href="{{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? ''}}">{{$title}}</a></h4>
                                    <p class="agency_work__para mt-3">{{$data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? ''}}</p>
                                    <div class="btn-wrapper mt-4">
                                        <a href="{{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? ''}}" class="agency_service__btn">{{$data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? ''}}</a>
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
