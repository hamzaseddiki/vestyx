@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
	$lotti = array("https://lottie.host/f775155b-526b-44b5-83e4-878af746e74b/nIi2kONcrN.json","https://lottie.host/de907634-6885-4c0b-afbd-c965f489e8c8/x4J6JgajrO.json");
@endphp

<section class="categoriesArea section-bg section-padding" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                <div class="section-tittle text-center mb-60">
                   {!! get_landlord_modified_title($data['title']) !!}
                </div>
            </div>
        </div>
        <div class="row">
			
            @foreach($data['repeater_data']['repeater_title_'.$current_lang] ?? [] as $key => $r_title)
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="singleCat mb-24 wow fadeInLeft" data-wow-delay="0.1s">
                        <div class="cat-icon">
                           {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.$current_lang][$key] ?? '') !!}
                        </div>
                        <div class="cat-cap">
                            <h5><a href="{{$data['repeater_data']['repeater_title_url_'.$current_lang][$key] ?? ''}}" class="tittle">{{$r_title ?? ''}}</a></h5>
                            <p class="pera">{{$data['repeater_data']['repeater_subtitle_'.$current_lang][$key] ?? ''}}</p>
                        </div>
                    </div>
                </div>
             @endforeach
        </div>
    </div>
</section>
