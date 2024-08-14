<div class="ourBranding-global section-padding2">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        @if(!empty($data['title']))
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-md-10">
                <div class="section-tittle text-center mb-40">
                    <h2 class="tittle wow ladeInUp" data-wow-delay="0.0s">{{$data['title']}} </h2>
                </div>
            </div>
        </div>
        @endif
        <div class="row ">
            <div class="col-xl-12">
                <div class="brandWrapper global-slick-init slider-inner-margin" data-infinite="false" data-arrows="false" data-dots="false" data-slidesToShow="6" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"  data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 6}},{"breakpoint": 1600,"settings": {"slidesToShow": 6}},{"breakpoint": 1400,"settings": {"slidesToShow": 5}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 992,"settings": {"slidesToShow": 4}},{"breakpoint": 768, "settings": {"slidesToShow": 2}},{"breakpoint": 480, "settings": {"slidesToShow": 1}}]'>
                  @foreach($data['brands'] as $data)
                    <div class="client-single wow ladeInRight" data-wow-delay="0.2s">
                        <a href="{{$data->url}}">
                            {!! render_image_markup_by_attachment_id($data->image) !!}
                        </a>
                    </div>
                   @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
