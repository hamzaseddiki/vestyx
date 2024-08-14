<div class="agency_banner agency_section_bg">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row align-items-center justify-content-center flex-column-reverse flex-lg-row">
            <div class="col-lg-6">
                <div class="agency_banner__content__wrapper">
                    <div class="agency_banner__single">
                        <div class="agency_banner__single__content">
                            <h2 class="agency_banner__single__content__title fw-600">
                                <span class="agency_banner_title_shape">
                                 <img src="{{global_asset('assets/tenant/frontend/themes/img/agency/banner/agency_banner_title_shape.svg')}}" alt="">

                                {!! get_modified_title_agency($data['title']) !!}
                            </h2>
                            <p class="agency_banner__single__content__para mt-3"> {{$data['description']}}</p>
                            <div class="btn-wrapper">
                                <a href="{{$data['button_url']}}" class="cmn-agency-btn cmn-agency-btn-bg-1 radius-0 mt-4 mt-lg-5"> {{$data['button_text']}} </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="agency_banner__right">
                    <div class="agency_banner_main__thumb thumb_shape">
                        {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                    </div>
                    <div class="agency_banner_reviewer agency_banner_reviewer__position">
                        <div class="agency_banner_reviewer__flex d-flex">
                            <div class="agency_banner_reviewer__thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/agency/banner/agency_reviewer1.jpg')}}" alt="reviewer">
                                </a>
                            </div>
                            <div class="agency_banner_reviewer__thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/agency/banner/agency_reviewer2.jpg')}}" alt="reviewer">
                                </a>
                            </div>
                            <div class="agency_banner_reviewer__thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/agency/banner/agency_reviewer3.jpg')}}" alt="reviewer">
                                </a>
                            </div>
                            <div class="agency_banner_reviewer__thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/agency/banner/agency_reviewer4.jpg')}}" alt="reviewer">
                                </a>
                            </div>
                            <div class="agency_banner_reviewer__thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/agency/banner/agency_reviewer5.jpg')}}" alt="reviewer">
                                </a>
                            </div>
                        </div>
                        <h4 class="agency_banner_reviewer__title mt-3"><a href="javascript:void(0)">{{$data['bottom_title']}}</a></h4>
                        <div class="agency_banner_reviewer__review mt-2">
                            <span class="agency_banner_reviewer__review__icon"><i class="las la-star"></i></span>
                            <b>{{$data['bottom_rating']}}</b>
                            <span>{{$data['bottom_subtitle']}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
