<section class="consulting_service_area padding-top-100 padding-bottom-100" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="body_border">
        <span class="one"></span>
        <span class="two"></span>
        <span class="three"></span>
        <span class="four"></span>
        <span class="five"></span>
        <span class="six"></span>
        <span class="seven"></span>
    </div>
    <div class="container">
        <div class="consulting_sectionTitle">
            <span class="subtitle">{{$data['title']}}</span>
              {!! get_consultancy_subtitle_line_breaker($data['subtitle']) !!}
        </div>
        <div class="row g-4 mt-4">
            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                <div class="col-lg-4 col-md-6">
                    <div class="consulting_service consulting_section_bg_2 radius-10">
                        <div class="consulting_service__icon radius-5">
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '' ) !!}
                        </div>
                        <div class="consulting_service__contents mt-3">
                            <h4 class="consulting_service__title">
                                <a href="{{$data['repeater_data']['repeater_title_url_'.get_user_lang()][$key] ?? '' }}">{{$title ?? ''}}</a>
                            </h4>
                            <p class="consulting_service__para mt-2"> {{$data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '' }}</p>
                            <div class="btn-wrapper mt-4">
                                <a href="{{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '' }}" class="consulting_service__btn radius-5">
                                    {{$data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? '' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
