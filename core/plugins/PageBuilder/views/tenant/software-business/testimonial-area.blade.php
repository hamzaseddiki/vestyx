<section class="softwareFirm_testimonial_area padding-top-50 padding-bottom-100"
data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="softwareFirm_sectionTitle title_flex text-left">
            <h2 class="title">{{$data['title']}}</h2>
            <div class="append_testimonial"></div>
        </div>
        <div class="row g-4 mt-4">
            <div class="global-slick-init project-slider nav-style-one slider-inner-margin" data-rtl="{{ get_slider_language_deriection() }}" data-appendArrows=".append_testimonial" data-arrows="true" data-infinite="false" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                 data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>

               @foreach($data['testimonial'] ?? [] as $item)
                    <div class="slick_slider_item">
                        <div class="softwareFirm_testimonial__single radius-10">
                            <div class="softwareFirm_testimonial__single__flex">
                                <div class="softwareFirm_testimonial__single__left">
                                    <div class="softwareFirm_testimonial__single__thumb">
                                        <a href="javascript:void(0)">
                                            {!! render_image_markup_by_attachment_id($item->image) !!}
                                        </a>
                                    </div>
                                    <div class="softwareFirm_testimonial__single__contents">
                                        <h4 class="softwareFirm_testimonial__single__contents__title"><a href="javascript:void(0)">{{$item->name}}</a></h4>
                                        <span class="softwareFirm_testimonial__single__contents__subtitle mt-1">{{$item->designation}}</span>
                                    </div>
                                </div>
                                <div class="softwareFirm_testimonial__single__right">
                                    <div class="softwareFirm_testimonial__single__review mt-2">
                                        <span class="softwareFirm_testimonial__single__review__para">5</span>
                                        <span class="softwareFirm_testimonial__single__review__icon"><i class="fa-solid fa-star"></i></span>
                                    </div>
                                </div>
                            </div>
                            <p class="softwareFirm_testimonial__single__contents__para mt-3">{!! Str::words($item->description,30) !!}</p>
                        </div>
                    </div>
               @endforeach
            </div>
        </div>
    </div>
</section>
