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

<section class="themes-area section-bg-1" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}" id="{{$data['section_id']}}">
    <div class="theme-shape">
        {!! render_image_markup_by_attachment_id($data['bg_shape_image']) !!}
    </div>
    <div class="container">
        <div class="section-title">
            {!! $final_title !!}
            <p class="section-para"> {{$data['subtitle']}} </p>
        </div>
        <div class="row mt-4">
            @foreach($data['themes'] as $key => $theme)
                <div class="col-lg-4 col-sm-6 mt-4">
                <div class="single-themes">
                    <div class="single-themes-thumb">
                        <a href="">
                            <img src="{{get_theme_image($theme->slug)}}" alt="">
                        </a>
                    </div>
                    <div class="single-themes-content mt-3">
                        <div class="single-themes-content-flex">
                            <h3 class="single-themes-content-title">
                                <a href=""> {{$theme->getTranslation('title', $current_lang)}} </a>
                            </h3>
                            <a href="" class="single-themes-content-title-icon">
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
