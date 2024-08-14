<section class="simpleContactArea section-padding wow fadeInUp" data-wow-delay="0.0s">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
            <div class="col-xxl-12">
                <div class="simpleContact text-center"{!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-lg-7 col-md-9 col-sm-11">
                            <h3 class="tittle wow fadeInUp" data-wow-delay="0.0s">{{$data['title']}}</h3>
                            <p class="pera wow fadeInUp" data-wow-delay="0.1s" >{{$data['description']}}</p>
                            <div class="btn-wrapper wow fadeInUp" data-wow-delay="0.4s">
                                <a href="{{$data['button_url']}}" class="cmn-btn2">{{$data['button_text']}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
