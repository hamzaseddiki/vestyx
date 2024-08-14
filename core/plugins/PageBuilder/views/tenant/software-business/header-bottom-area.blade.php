
<section class="softwareFirm_bannerbottom_area padding-top-100 padding-bottom-100"
                            data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row gy-5 justify-content-between">
            <div class="col-lg-5">
                <div class="softwareFirm_bannerbottom__wrapper">
                    <h2 class="softwareFirm_bannerbottom__wrapper__title">{{$data['left_title']}}</h2>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="softwareFirm_bannerbottom__contents">
                    <p class="softwareFirm_bannerbottom__contents__para">{{$data['right_description']}}</p>
                    <div class="btn-wrapper mt-4">
                        <a href="{{$data['button_url']}}" class="softwareFirm_cmn_btn btn_bg_1 radius-10">{{$data['button_text']}}<i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-4">
            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $ti)
                @php
                    $title = $data['repeater_data']['repeater_title_'.get_user_lang()][$key] ?? '';
                    $description = $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '';
                    $more_text = $data['repeater_data']['repeater_more_text_'.get_user_lang()][$key] ?? '';
                    $more_url = $data['repeater_data']['repeater_more_url_'.get_user_lang()][$key] ?? '';
                    $image = $data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '';
                @endphp
                <div class="col-xl-4 col-lg-4 col-sm-6">
                    <div class="softwareFirm_bannerbottom__single radius-10">
                        <div class="softwareFirm_bannerbottom__single__icon">
                            {!! render_image_markup_by_attachment_id($image) !!}
                        </div>
                        <div class="softwareFirm_bannerbottom__single__contents mt-3">
                            <h4 class="softwareFirm_bannerbottom__single__title">{{$title}}</h4>
                            <p class="softwareFirm_bannerbottom__single__para mt-3">{{$description}}</p>
                            <div class="btn-wrapper mt-4">
                                <a href="{{$more_url}}" class="softwareFirm_bannerbottom__single__learnMore">{{$more_text}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
