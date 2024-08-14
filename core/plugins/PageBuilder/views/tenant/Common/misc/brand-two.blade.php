
<div class="ourBranding section-padding2 ">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-10 col-md-10 col-sm-10">
                <div class="section-tittle section-tittle2 text-center mb-40">
                    @php
                        $main_title = $data['title'];
                        $exploded = explode(' ',$main_title);
                        $after_exploded = $exploded;
                        $first_two_words = array_slice($exploded,0,2);
                        $last_three_words = array_slice($exploded,-4,4);
                        $middle_words = array_diff($after_exploded, array_merge($first_two_words,$last_three_words));
                    @endphp

                    <h2 class="tittle"> <span class="textColor"> {{ implode(' ', $first_two_words) }} </span>
                        {{ implode(' ',$middle_words) }} <span class="textColor">{{ implode(' ',$last_three_words) }}</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="brandWrapper wrapperStyleOne global-slick-init slider-inner-margin" data-infinite="false" data-arrows="false" data-dots="false" data-slidesToShow="5" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"  data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 5}},{"breakpoint": 1600,"settings": {"slidesToShow": 5}},{"breakpoint": 1400,"settings": {"slidesToShow": 5}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 992,"settings": {"slidesToShow": 4}},{"breakpoint": 768, "settings": {"slidesToShow": 2}},{"breakpoint": 480, "settings": {"slidesToShow": 1}}]'>
                  @foreach($data['brands']['repeater_url_'] ?? [] as $key => $brand_url)
                        <div class="client-single wow fadeInLeft" data-wow-delay="0.1s">
                            <a href="{{$brand_url}}">
                                {!! render_image_markup_by_attachment_id($data['brands']['repeater_image_'][$key] ?? '') !!}
                            </a>
                        </div>
                  @endforeach
                </div>
            </div>
        </div>
    </div>
</div>






