@php
    $lang_direction = get_user_lang_direction();
    $land_direction_condition = $lang_direction == 1 ? 'true' : 'false';
@endphp
<div class="ourBranding" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row ">
            <div class="col-xl-12">
                <div class="ServicesWrapper global-slick-init slider-inner-margin" data-rtl="{{$land_direction_condition}}" data-infinite="true" data-arrows="false" data-dots="false" data-slidesToShow="5" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"  data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 5}},{"breakpoint": 1600,"settings": {"slidesToShow": 5}},{"breakpoint": 1400,"settings": {"slidesToShow": 5}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 992,"settings": {"slidesToShow": 4}},{"breakpoint": 768, "settings": {"slidesToShow": 2}},{"breakpoint": 480, "settings": {"slidesToShow": 1}}]'>
                    @foreach($data['brands'] as $data)
                    <div class="client-single wow fadeInLeft" data-wow-delay="0.1s">
                        <a href="{{$data->url}}">{!! render_image_markup_by_attachment_id($data->image) !!}</a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
