@php
    $user_lang = get_user_lang();
@endphp

<section class="categoriesTwo sectionBg1 section-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <div class="singleCat text-center mb-24 wow fadeInRight" data-wow-delay="0.0s">
                        <div class="cat-icon">
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.$user_lang][$key]) !!}
                        </div>
                        <div class="cat-cap">
                            <h5><a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}" class="tittle">{{$title ?? ''}}</a></h5>
                            <p class="pera">{{ $data['repeater_data']['repeater_subtitle_'.$user_lang][$key] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
