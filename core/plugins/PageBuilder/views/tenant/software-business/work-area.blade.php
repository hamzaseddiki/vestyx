<section class="softwareFirm_work_area softwareFirm-section-bg padding-top-100 padding-bottom-100"
data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="softwareFirm_work__shapes">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="softwareFirm_work__shapesText">
        <h2 class="softwareFirm_work__shapesText__item">Techno</h2>
        <h2 class="softwareFirm_work__shapesText__item">Techno</h2>
    </div>
    <div class="container">
        <div class="softwareFirm_sectionTitle">
            <h2 class="title">{{$data['title']}} </h2>
        </div>
        <div class="row g-4 mt-4">
           @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $ti)
               @php
                   $title = $ti ?? '';
                   $subtitle = $data['repeater_data']['repeater_subtitle_'.get_user_lang()][$key] ?? '';
                   $url = $data['repeater_data']['repeater_url_'.get_user_lang()][$key] ?? '';
                   $image = $data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '';
               @endphp
                <div class="col-lg-4 col-sm-6">
                    <div class="softwareFirm__work radius-10">
                        <div class="softwareFirm__work__thumb">
                            <a href="{{$url}}">
                                {!! render_image_markup_by_attachment_id($image) !!}
                            </a>
                        </div>
                        <div class="softwareFirm__work__contents">
                            <div class="softwareFirm__work__contents__details">
                                <h4 class="softwareFirm__work__title">
                                    <a href="{{$url}}">{{$title}}</a>
                                </h4>
                                <p class="softwareFirm__work__para mt-1">{{$subtitle}}</p>
                            </div>
                            <a href="{{$url}}" class="softwareFirm__work__arrowBtn"><i class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
           @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                <div class="btn-wraper center-text mt-4 mt-lg-5">
                    <a href="{{$data['button_url']}}" class="softwareFirm_cmn_btn btn_bg_1 radius-10">{{$data['button_text']}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
