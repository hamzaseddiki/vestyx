
<div class="videoGallery section-padding2">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-10">
                <div class="section-tittle text-center mb-50">
                    <h2 class="tittle wow ladeInUp animated" data-wow-delay="0.0s">{{$data['title']}}</h2>
                    <p>{{$data['subtitle']}}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- Product Slider -->
                <div class="product-view-wrap" id="myTabContent">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="shop-details-gallery-slider global-slick-init slider-inner-margin sliderArrow mb-30"
                                 data-asNavFor=".shop-details-gallery-nav" data-infinite="false" data-arrows="false" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-fade="true"  data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                                 data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 1}},{"breakpoint": 1600,"settings": {"slidesToShow": 1}},{"breakpoint": 1400,"settings": {"slidesToShow": 1}},{"breakpoint": 1200,"settings": {"slidesToShow": 1}},{"breakpoint": 991,"settings": {"slidesToShow": 1}},{"breakpoint": 768, "settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>

                                @foreach($data['repeater_data']['repeater_url_'] as $key => $url)
                                     <div class="single-main-image">
                                        <a href="#" class="long-img">
                                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_thumbnail_'][$key],'w-100') ?? '' !!}
                                        </a>

                                        <!-- Video icon -->
                                        <div class="video-icon">
                                            <a class="popup-video btn-icon" href="{{ $url }}">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <!-- Nav -->
                            <div class="thumb-wrap">
                                <div class="shop-details-gallery-nav global-slick-init slider-inner-margin sliderArrow"
                                     data-asNavFor=".shop-details-gallery-slider" data-focusOnSelect="true"  data-infinite="true" data-arrows="false" data-dots="false" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                                     data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 991,"settings": {"slidesToShow": 4}},{"breakpoint": 768, "settings": {"slidesToShow": 4}},{"breakpoint": 576, "settings": {"slidesToShow": 4}}]'>

                                    @foreach($data['repeater_data']['repeater_url_'] as $key => $url)
                                        <div class="single-thumb">
                                            <a class="thumb-link" data-toggle="tab" href="#image-0{{$loop->iteration}}">
                                                {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_thumbnail_'][$key]) ?? '' !!}
                                            </a>
                                       </div>
                                     @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
