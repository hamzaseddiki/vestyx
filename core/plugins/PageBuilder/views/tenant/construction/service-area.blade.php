<section class="construction_bannerbottom_area padding-top-100 padding-bottom-100">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row gy-5 justify-content-between">
            <div class="col-lg-6">
                <div class="construction_bannerbottom__wrapper">
                    <h2 class="construction_bannerbottom__wrapper__title">{{$data['title']}}</h2>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="construction_bannerbottom__contents">
                    <p class="construction_bannerbottom__contents__para">{{$data['description']}}</p>
                    <div class="btn-wrapper mt-4">
                        <a href="{{$data['button_url']}}" class="construction_cmn_btn btn_bg_1 radius-10">
                            {{$data['button_text']}} <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-4">
            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="construction_bannerbottom__single radius-10">
                        <div class="construction_bannerbottom__single__icon">
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '' ) !!}
                        </div>
                        <div class="construction_bannerbottom__single__contents mt-3">
                            <a href="{{$data['repeater_data']['repeater_title_url_'.get_user_lang()][$key] ?? ''}}">
                                 <h4 class="construction_bannerbottom__single__title">{{$title ?? ''}}</h4>
                            </a>
                            <p class="construction_bannerbottom__single__para mt-3">{{$data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? ''}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
