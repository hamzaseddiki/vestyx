
<section class="portfolio_testimonial_area padding-top-50 padding-bottom-50" data-padding-top="{{$data['padding_top']}}"
data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="portfolio_sectionTitle">
         {!! get_modified_title_portfolio($data['title']) !!}
        </div>
        <div class="row g-4 mt-4">
            <div class="global-slick-init project-slider dot-style-one slider-inner-margin" data-rtl="{{ get_slider_language_deriection() }}" data-arrows="false" data-infinite="false" data-dots="true" data-slidesToShow="3" data-swipeToSlide="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                 data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 2}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>

                @foreach($data['repeater_data']['repeater_name_'.get_user_lang()] ?? [] as $key => $ti)
                    @php
                        $name = $ti;
                        $designation = $data['repeater_data']['repeater_designation_'.get_user_lang()][$key] ?? '';
                        $description = $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '';
                        $image = $data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '';
                    @endphp
                    <div class="slick_slider_item">
                        <div class="portfolio_testimonial__single radius-10">
                            <div class="portfolio_testimonial__single__flex">
                                <div class="portfolio_testimonial__single__thumb">
                                    <a href="javascript:void(0)">
                                        {!! render_image_markup_by_attachment_id($image) !!}
                                    </a>
                                </div>
                                <div class="portfolio_testimonial__single__contents">
                                    <h4 class="portfolio_testimonial__single__contents__title"><a href="javascript:void(0)">{{$name}}</a></h4>
                                    <span class="portfolio_testimonial__single__contents__subtitle mt-1">{{$designation}}</span>
                                </div>
                            </div>
                            <p class="portfolio_testimonial__single__contents__para mt-3">{{$description}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
