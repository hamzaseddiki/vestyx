
<section class="consulting_team_area padding-top-100 padding-bottom-50" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
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
           @foreach($data['repeater_data']['repeater_name_'.get_user_lang()] ?? [] as $key=> $name)
                <div class="col-lg-4 col-sm-6">
                    <div class="consulting_team radius-10">
                        <div class="consulting_team__thumb">
                            <a href="{{ $data['repeater_data']['repeater_url_'.get_user_lang()][$key] ?? '' }}">
                                {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '') !!}
                            </a>
                        </div>
                        <div class="consulting_team__contents">
                            <h4 class="consulting_team__title">
                                <a href="{{$data['repeater_data']['repeater_url_'.get_user_lang()][$key] ?? ''}}">{{$name ?? ''}}</a>
                            </h4>
                            <span class="consulting_team__expert">{{$data['repeater_data']['repeater_expertise_text_'.get_user_lang()][$key] ?? ''}}</span>
                            <p class="consulting_team__para">{{$data['repeater_data']['repeater_designation_'.get_user_lang()][$key] ?? ''}}</p>
                        </div>
                    </div>
                </div>
           @endforeach
        </div>
        <div class="row mt-4 mt-lg-5">
            <div class="col-12">
                <div class="team_btn btn-wrapper center-text">
                    <a href="{{$data['button_url']}}" class="consulting_cmn_btn btn_bg_1 radius-10">{{$data['button_text']}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
