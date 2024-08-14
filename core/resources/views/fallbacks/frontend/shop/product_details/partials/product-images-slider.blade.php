
<div class="col-lg-6">
    <div class="product-view-wrap mb-50">
        <div class="shop-details-gallery-slider global-slick-init slider-inner-margin sliderArrow mb-30"
             data-asNavFor=".shop-details-gallery-nav" data-infinite="false" data-rtl="{{get_user_lang_direction() == 1 ? 'true' : 'false'}}" data-arrows="false" data-dots="false" data-slidesToShow="1" data-swipeToSlide="false" data-fade="false"  data-autoplay="false" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
             data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 1}},{"breakpoint": 1600,"settings": {"slidesToShow": 1}},{"breakpoint": 1400,"settings": {"slidesToShow": 1}},{"breakpoint": 1200,"settings": {"slidesToShow": 1}},{"breakpoint": 991,"settings": {"slidesToShow": 1}},{"breakpoint": 768, "settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>
            @php
                $image_array = array();
                array_push($image_array, (int)$product->image_id);

                if (count($product->gallery_images) > 0)
                {
                    foreach ($product->gallery_images ?? [] as $image)
                    {
                        array_push($image_array, $image->id);
                    }
                }
            @endphp

            @if(count($image_array) > 0)
                @foreach($image_array ?? []  as $gallery)
                    <div class="single-main-image">
                        <a href="#!" class="long-img">
                            {!! render_image_markup_by_attachment_id($gallery, 'w-100') !!}
                        </a>
                    </div>
                @endforeach
             @else
                <div class="single-main-image">
                    <a href="#!" class="long-img">
                        {!! render_image_markup_by_attachment_id($product->image_id, 'w-100') !!}
                    </a>
                </div>
             @endif
        </div>


        @if(count($image_array) > 0)
        <div class="thumb-wrap">
            <div class="shop-details-gallery-nav nav-style-two global-slick-init slider-inner-margin sliderArrow"
                 data-asNavFor=".shop-details-gallery-slider" data-rtl="{{get_user_lang_direction() == 1 ? 'true' : 'false'}}" data-focusOnSelect="true" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="4" data-swipeToSlide="false" data-autoplay="false" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                 data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 991,"settings": {"slidesToShow": 4}},{"breakpoint": 768, "settings": {"slidesToShow": 4}},{"breakpoint": 576, "settings": {"slidesToShow": 4}}]'>
                @foreach($image_array ?? []  as $gallery)
                    <div class="single-thumb">
                        <a class="thumb-link" data-toggle="tab" href="#image-0{{$loop->iteration}}">
                            {!! render_image_markup_by_attachment_id($gallery) !!}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
       @endif
    </div>
</div>
