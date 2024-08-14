
<section class="agency_service_area agency_section_bg_2 padding-top-100 padding-bottom-100">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="agency_section__title white">
            {!! get_modified_title_agency_two($data['title']) !!}
        </div>
        <div class="row g-4 mt-4">
            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="agency_service bg-white thumb_shape">
                        <div class="agency_service__icon">
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '') !!}
                        </div>
                        <div class="agency_service__contents mt-3">
                            <h4 class="agency_service__title">
                                <a href="{{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? ''}}">{{$title}}</a>
                            </h4>
                            <p class="agency_service__para mt-2">{{$data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? ''}}</p>
                            <div class="btn-wrapper mt-4">
                                <a href="{{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? ''}}" class="agency_service__btn">{{$data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? ''}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
