<section class="abutArea sectionBg1 section-padding2">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-between align-items-center">
            <div class="col-xxl-6 col-xl-5 col-lg-6">
                <div class="aboutImg tilt-effect wow fadeInLeft" data-wow-delay="0.1s">
                    {!! render_image_markup_by_attachment_id($data['left_image']) !!}
                </div>
            </div>
            <div class="col-xxl-6 col-xl-7 col-lg-6">

                <div class="aboutCaption">
                    <div class="section-tittle mb-25">

                         {!! get_modified_title_ticket($data['title']) !!}

                        <p class="pera wow fadeInUp" data-wow-delay="0.1s">{{$data['description']}}</p>
                        <div class="btn-wrapper mt-50 wow fadeInUp" data-wow-delay="0.3s">
                            <a href="{{$data['button_url']}}" class="cmn-btn4">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
