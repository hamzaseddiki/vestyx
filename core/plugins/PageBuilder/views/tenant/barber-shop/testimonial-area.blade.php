<section class="barberShop_testimonial_area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="barberShop_sectionTitle title_flex text-left">

            {!! get_modified_title_barber_two($data['title']) !!}

            <div class="append_testimonial"></div>
        </div>
        <div class="row g-4 mt-4">
            <div class="global-slick-init nav-style-one slider-inner-margin" data-rtl="{{ get_slider_language_deriection() }}" data-appendArrows=".append_testimonial" data-arrows="true" data-infinite="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                 data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>

                @foreach($data['testimonial'] ?? [] as $item_bottom)
                <div class="slick_slider_item">
                    <div class="barberShop_testimonial__single barberShop-section-bg">
                        <div class="barberShop_testimonial__single__flex">
                            <div class="barberShop_testimonial__single__left">
                                <div class="barberShop_testimonial__single__thumb">
                                    <a href="javascript:void(0)">
                                        {!! render_image_markup_by_attachment_id($item_bottom->image) !!}
                                    </a>
                                </div>
                                <div class="barberShop_testimonial__single__contents">
                                    <h4 class="barberShop_testimonial__single__contents__title"><a href="javascript:void(0)">{{$item_bottom->name}}</a></h4>
                                    <span class="barberShop_testimonial__single__contents__subtitle mt-1">{{$item_bottom->designation}}</span>
                                </div>
                            </div>
                            <div class="barberShop_testimonial__single__right">
                                <div class="barberShop_testimonial__single__review mt-2">
                                    <span class="barberShop_testimonial__single__review__icon"><i class="fa-solid fa-star"></i></span>
                                    <span class="barberShop_testimonial__single__review__para">4.5</span>
                                </div>
                            </div>
                        </div>
                        <p class="barberShop_testimonial__single__contents__para mt-3">{!! \Illuminate\Support\Str::words($item_bottom->getTranslation('description',get_user_lang()),30) !!}</p>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
