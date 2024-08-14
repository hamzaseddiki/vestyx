

<section class="wedding_bannerbottom_area position-relative wedding-main-gradient padding-top-100 padding-bottom-100" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row g-5">

            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                <div class="{{$data['column'] ?? 'col-lg-6'}}">
                    <div class="wedding_bannerbottom__single white radius-10">
                        <div class="wedding_bannerbottom__single__flex">
                            <div class="wedding_bannerbottom__single__thumb">
                                {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '') !!}
                            </div>
                            <div class="wedding_bannerbottom__single__contents">
                                <a href="{{$data['repeater_data']['repeater_title_url_'.get_user_lang()][$key] ?? ''}}">
                                    <h4 class="wedding_bannerbottom__single__title">{{$title ?? '' }}</h4>
                                </a>
                                <p class="wedding_bannerbottom__single__para mt-3">{{$data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? ''}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
