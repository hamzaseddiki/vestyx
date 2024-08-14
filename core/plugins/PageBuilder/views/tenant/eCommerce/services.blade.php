<div class="ourServices">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row ServicesWrapper">
            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="client-single mb-20 wow fadeInLeft" data-wow-delay="0.1s">
                        <blockquote>
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? null) !!}
                        </blockquote>
                        <div class="servicesCap">
                            <a href="{{ $data['repeater_data']['repeater_title_url_'.get_user_lang()][$key] ?? ''}}">
                                <h2 class="title">{{$title ?? ''}}</h2>
                            </a>

                            <p class="pear">{{$data['repeater_data']['repeater_subtitle_'.get_user_lang()][$key] ?? ''}}</p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
