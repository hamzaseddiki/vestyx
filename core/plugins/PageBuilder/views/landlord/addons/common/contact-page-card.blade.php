@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp

<section class="categoriesTwo section-bg section-padding" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            @foreach($data['repeater_data']['repeater_title_'.$current_lang] as $key => $ti)
                @php
                    $title = $ti ?? '';
                    $info = $data['repeater_data']['repeater_info_'.$current_lang][$key] ?? '';
                    $image = $data['repeater_data']['repeater_image_'.$current_lang][$key] ?? '';
                    $explode_info = explode("\n",$info);
                @endphp
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="singleCat text-center mb-24 wow fadeInUp" data-wow-delay="0.0s">
                        <div class="cat-icon">
                            {!! render_image_markup_by_attachment_id($image) !!}
                        </div>
                        <div class="cat-cap">
                            <h5><a href="#" class="tittle">{{$title}}</a></h5>
                            @foreach($explode_info as $item)
                                <p class="pera">{{$item}}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
