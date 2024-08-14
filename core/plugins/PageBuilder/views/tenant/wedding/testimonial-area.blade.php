<section class="wedding_testimonial_area position-relative padding-top-50 padding-bottom-50" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="gradient_bg">
        <span></span>
        <span></span>
    </div>
    <div class="wedding_testimonial__shapes">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/testimonial/wedding_hearts.png')}}" alt="">
    </div>

    <div class="container">
        <div class="wedding_sectionTitle">
            <h2 class="title">{{$data['title']}}</h2>
        </div>
        <div class="row g-4 mt-4">
            <div class="global-slick-init project-slider dot-style-one slider-inner-margin" data-rtl="{{ get_slider_language_deriection() }}" data-arrows="false" data-infinite="false" data-dots="true" data-slidesToShow="3" data-swipeToSlide="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                 data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 2}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>

                @foreach($data['testimonial'] ?? [] as $item)
                    <div class="slick_slider_item">
                        <div class="wedding_testimonial__single radius-10">
                            <div class="wedding_testimonial__single__flex">
                                <div class="wedding_testimonial__single__thumb">
                                    <a href="javascript:void(0)">
                                        {!! render_image_markup_by_attachment_id($item->image) !!}
                                    </a>
                                </div>
                                <div class="wedding_testimonial__single__contents">
                                    <h4 class="wedding_testimonial__single__contents__title"><a href="javascript:void(0)">{{$item->name}}</a></h4>
                                    <span class="wedding_testimonial__single__contents__subtitle mt-1">{{$item->designation}}</span>
                                </div>
                            </div>

                            <h4 class="wedding_testimonial__single__contents__title mt-4">{{$data['inner_text']}}</h4>
                            <p class="wedding_testimonial__single__contents__para mt-3">{!! \Illuminate\Support\Str::words($item->description,30) !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
