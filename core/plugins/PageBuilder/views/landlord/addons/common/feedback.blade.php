@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();

    if (str_contains($data['title'], '{h}') && str_contains($data['title'], '{/h}'))
    {
        $text = explode('{h}',$data['title']);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="section-shape">'. $highlighted_word .'</span>';
        $final_title = '<h2 class="title">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $data['title']).'</h2>';
    } else {
        $final_title = '<h2 class="title">'. $data['title'] .'</h2>';
    }
@endphp

<section class="feedback-area section-bg-1" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}"id="{{$data['section_id']}}">
    <div class="container">
        <div class="section-title">
            {!! $final_title !!}
            <p class="section-para"> {{$data['subtitle']}} </p>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mt-4">
                <div class="global-slick-init slider-inner-margin dot-style-one" data-infinite="true" data-arrows="false" data-dots="true" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-responsive='[{"breakpoint": 1600,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>
                    @foreach($data['testimonial'] ?? [] as $info)
                        <div class="slick-slider-item">
                        <div class="single-feedback bg-white radius-10">
                            <div class="single-feedback-content">
                                <span class="single-feedback-content-icon"> <i class="las la-quote-left"></i> </span>
                                <p class="single-feedback-content-para"> {!! $info->getTranslation('description',$current_lang) ?? '' !!} </p>
                            </div>
                            <div class="single-feedback-author author-border">
                                <div class="single-feedback-author-flex">
                                    <div class="single-feedback-author-thumb">
                                        <a href="javascript:void(0)">
                                            {!! render_image_markup_by_attachment_id($info->image) !!}
                                        </a>
                                    </div>
                                    <div class="single-feedback-author-content">
                                        <h3 class="single-feedback-author-content-title"> <a href="javascript:void(0)"> {{$info->getTranslation('name',$current_lang) ?? ''}}</a> </h3>
                                        <span class="single-feedback-author-content-subtitle"> {{$info->getTranslation('company',$current_lang) ?? ''}} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
