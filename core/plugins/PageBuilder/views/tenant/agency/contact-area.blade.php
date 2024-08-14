<section class="agency_contact_footer agency_section_bg_3 padding-bottom-100 padding-top-100">
    <div class="agency_contact_footer_shape">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/agency/footer/agency_footer_shape.svg')}}" alt="">
    </div>
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
            <div class="col-12">
                <div class="agency_contact_single">
                    <div class="agency_contact_single__flex">
                        <div class="agency_contact_single__left">
                            <div class="agency_section__title text-left white">
                                <h2 class="title">

                                {!! get_modified_title_agency($data['title']) !!}

                                    <span class="agency_title_shape">
                                        <img src="{{global_asset('assets/tenant/frontend/themes/img/agency/banner/agency_banner_title_shape.svg')}}" alt=""></span>
                                </h2>
                            </div>
                        </div>
                        <div class="btn-wrapper">
                            <a href="{{$data['button_url']}}" class="cmn-agency-btn cmn-agency-btn-bg-1">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
