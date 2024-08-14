<section class="agency_about padding-top-100 padding-bottom-100">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row g-4 align-items-center justify-content-between">
            <div class="col-xl-6 col-lg-6">
                <div class="agency_about__left thumb_border">
                    <div class="agency_about__thumb thumb_shape">
                        {!! render_image_markup_by_attachment_id($data['left_image']) !!}
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-6">
                <div class="agency_about__right">
                    <div class="agency_section__title text-left">
                        <h2 class="title">
                            {!! get_modified_title_agency($data['title']) !!}
                            <span class="agency_title_shape">
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/agency/banner/agency_banner_title_shape.svg')}}" alt="">
                            </span>
                        </h2>
                        <p class="section_para">{{$data['description']}}</p>
                    </div>
                    <div class="agency_about__bottom mt-4 mt-lg-5">
                        <ul class="agency_about__list">
                            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                                <li><a href="{{$data['repeater_data']['repeater_url_'.get_user_lang()][$key]}}">{{$title}}</a></li>
                            @endforeach

                        </ul>
                        <div class="btn-wrapper mt-4 mt-lg-5">
                            <a href="{{$data['button_url']}}" class="cmn-agency-btn cmn-agency-btn-bg-1 radius-0">{!! $data['button_text'] !!}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
