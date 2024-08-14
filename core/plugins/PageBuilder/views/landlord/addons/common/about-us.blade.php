@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
    if (str_contains($data['title'], '{h}') && str_contains($data['title'], '{/h}'))
    {
        $text = explode('{h}',$data['title']);
        $highlighted_word = explode('{/h}', $text[1])[0];
        $highlighted_text = '<span class="color">'. $highlighted_word .'</span>';
        $final_title = '<h1 class="tittle wow fadeInUp" data-wow-delay="0.0s">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $data['title']).'</h2>';
    } else {
        $final_title = '<h1 class="tittle wow fadeInUp" data-wow-delay="0.0s">'. $data['title'] .'</h2>';
    }
@endphp

<section class="aboutArea section-padding" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="about-caption text-center">
                    <!-- Section Tittle -->
                    <div class="section-tittle section-tittle2 mb-40">
                      {!! $final_title !!}
                    </div>
                </div>
                <!-- about-img -->
                <div class="aboutImg">
                    {!! $data['description'] !!}
                </div>
            </div>

        </div>
    </div>
</section>
