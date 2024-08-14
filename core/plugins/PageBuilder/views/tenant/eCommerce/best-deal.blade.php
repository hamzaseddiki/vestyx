
<div class="bestDeals-Week bottom-padding2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                <div class="section-tittle text-center mb-50">
                    <h2 class="ttle">{{$data['title']}}</h2>
                </div>
            </div>
        </div>
        <div class="row">

            @php
                $colors = ['bgColorFour','bgColorFive','bgColorSix']
            @endphp

            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                <div class="col-xxl-4 col-xl-4 col-lg-6">
                    <div class="singleCart singleCartTwo mb-24 {{$colors[$key % count($colors)]}} tilt-effect wow fadeInLeft" data-wow-delay="0.1s">
                        <div class="itemsCaption">
                            <p class="itemsCap">{{$title ?? '' }}</p>
                            <h5><a href="#" class="itemsTittle">{{ $data['repeater_data']['repeater_subtitle_'.get_user_lang()][$key] ?? '' }}</a></h5>
                            <p class="pera">{{ $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '' }}</p>
                            <div class="btn-wrapper">
                                <a href="{{ $data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '' }}" class="cmn-btn4" tabindex="0">{{ $data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? '' }}</a>
                            </div>
                        </div>
                        <div class="itemsImg wow fadeInUp" data-wow-delay="0.0s">
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '' ) !!}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
