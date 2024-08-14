
<div class="sliderArea eventSlider heroImgBg hero-overly" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
        <div class="single-slider">
        <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
            <div class="row justify-content-between align-items-end">
                <div class="col-xxl-6 col-xl-6 col-lg-6">
                    <div class="heroCaption heroPadding">
                        <h1 class="tittle textEffect" data-animation="ladeInUp">{{$data['title']}}</h1>
                        <p class="pera" data-animation="ladeInUp" data-delay="0.3s">{!! $data['description'] !!}</p>

                        <div class="btn-wrapper d-flex align-items-center flex-wrap">
                            <a href="{{ $data['button_url'] }}" class="cmn-btn0 hero-btn mr-15 mb-15 " data-animation="ladeInLeft" data-delay="0.5s">{{ $data['button_text'] }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-xl-6 col-lg-6">
                    <div class="hero-man d-none d-lg-block f-right running" >
                        {!! render_image_markup_by_attachment_id($data['right_image'],'','','ladeInUp','0.2s') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
