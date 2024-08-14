
<section class="photography_testimonial_area padding-top-50 padding-bottom-50" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="photography_testimonial__shapes">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/photography/testimonial/photography_starShape.svg')}}" alt="">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/photography/testimonial/photography_cameraShape.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-lg-6">
                <div class="global-slick-init photography_testimonail_content dot-style-one slider-inner-margin" data-rtl="{{ get_slider_language_deriection() }}" data-asNavFor=".photography_testimonail_thumb" data-arrows="false" data-infinite="true" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 1}},{"breakpoint": 1200,"settings": {"slidesToShow": 1}},{"breakpoint": 992,"settings": {"slidesToShow": 1}},{"breakpoint": 768,"settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>

                    @foreach($data['repeater_data']['repeater_image_'.get_user_lang()] ?? [] as $key=> $img)
                        @php
                            $image = $img ?? '';
                        @endphp
                          <div class="slick_slider_item">
                            <div class="photography_testimonial__thumb">
                                <div class="photography_testimonial__thumb__main">
                                    <a href="javascript:void(0)">
                                      {!! render_image_markup_by_attachment_id($image) !!}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <div class="col-lg-6">
                <div class="global-slick-init photography_testimonail_thumb dot-style-one slider-inner-margin" data-rtl="{{ get_slider_language_deriection() }}" data-appendArrows=".photography_testimonial_appendNav" data-asNavFor=".photography_testimonail_content" data-arrows="true" data-infinite="true" data-dots="false" data-autoplay="true" data-slidesToShow="1" data-swipeToSlide="true" data-autoplaySpeed="4000" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left-long"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right-long"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 1}},{"breakpoint": 1200,"settings": {"slidesToShow": 1}},{"breakpoint": 992,"settings": {"slidesToShow": 1}},{"breakpoint": 768,"settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>
                    @foreach($data['repeater_data']['repeater_image_'.get_user_lang()] ?? [] as $key=> $img)
                        @php
                             $image = $img ?? '';
                             $name = $data['repeater_data']['repeater_name_'.get_user_lang()][$key] ?? '';
                             $description = $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '';
                             $address = $data['repeater_data']['repeater_address_'.get_user_lang()][$key] ?? '';
                        @endphp
                    <div class="slick_slider_item">
                        <div class="photography_testimonial">
                            <div class="photography_sectionTitle text-left">
                              {!! get_modified_title_photography($data['title']) !!}
                            </div>
                            <div class="photography_testimonial__contents mt-4">
                                <p class="photography_testimonial__para">{{$description}}</p>
                                <div class="photography_testimonial__contents__details mt-4 mt-lg-5">
                                    <div class="photography_testimonial__review">
                                        <span class="photography_testimonial__review__star"><i class="fa-solid fa-star"></i></span>
                                        <span class="photography_testimonial__review__star"><i class="fa-solid fa-star"></i></span>
                                        <span class="photography_testimonial__review__star"><i class="fa-solid fa-star"></i></span>
                                        <span class="photography_testimonial__review__star"><i class="fa-solid fa-star"></i></span>
                                        <span class="photography_testimonial__review__star"><i class="fa-solid fa-star"></i></span>
                                    </div>
                                    <h4 class="photography_testimonial__title mt-4">{{$name}}</h4>
                                    <span class="photography_testimonial__subtitle mt-2">{{$address}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="photography_testimonial_appendNav mt-4"></div>
            </div>
        </div>
    </div>
</section>
