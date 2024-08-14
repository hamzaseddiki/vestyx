@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
    if (str_contains($data['title'], '{h}') && str_contains($data['title'], '{/h}'))
    {
        $text = explode('{h}',$data['title']);
        $highlighted_word = explode('{/h}', $text[1])[0];
        $highlighted_text = '<span class="color">'. $highlighted_word .'</span>';
        $final_title = '<h2 class="tittle wow fadeInUp" data-wow-delay="0.0s">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $data['title']).'</h2>';
    } else {
        $final_title = '<h2 class="tittle wow fadeInUp" data-wow-delay="0.0s">'. $data['title'] .'</h2>';
    }
@endphp

<section class="teamArea section-padding" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-7 col-xl-7 col-lg-8 col-md-9 col-sm-10">
                <div class="section-tittle text-center mb-50">
                    {!! $final_title !!}
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($data['repeater_data']['repeater_name_'.$current_lang] ?? [] as $key => $nm )
                @php
                    $name = $nm ?? '';
                    $designation = $data['repeater_data']['repeater_designation_'.$current_lang][$key] ?? '';
                    $facebook_url = $data['repeater_data']['repeater_facebook_url_'.$current_lang][$key] ?? '';
                    $twitter_url = $data['repeater_data']['repeater_twitter_url_'.$current_lang][$key] ?? '';
                    $instagram_url = $data['repeater_data']['repeater_instagram_url_'.$current_lang][$key] ?? '';
                    $image = $data['repeater_data']['repeater_image_'.$current_lang][$key]  ?? '';
                @endphp
                  <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="singleTeam mb-24 wow fadeInLeft" data-wow-delay="0.3s">
                        <div class="teamImg">
                            {!! render_image_markup_by_attachment_id($image) !!}
                        </div>
                        <div class="teamCaption">
                            <h3><a href="#!" class="title">{{$name}}</a></h3>
                            <p class="pera">{{$designation}}</p>
                            <ul class="teamSocial">
                                <li class="list"><a href="{{$facebook_url}}" target="_blank" class="singleSocial"><i class="lab la-facebook-f icon"></i></a></li>
                                <li class="list"><a href="{{$twitter_url}}" target="_blank" class="singleSocial"><i class="lab la-twitter icon"></i></a></li>
                                <li class="list"><a href="{{$instagram_url}}" target="_blank" class="singleSocial"><i class="lab la-instagram icon"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


