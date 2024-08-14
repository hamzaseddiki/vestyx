<div class="consulting_banner_area consulting_section_bg consulting_banner__padding" data-padding-bottom="{{$data['padding_bottom']}}" data-padding-top="{{$data['padding_top']}}">
    <div class="body_border">
        <span class="one"></span>
        <span class="two"></span>
        <span class="three"></span>
        <span class="four"></span>
        <span class="five"></span>
        <span class="six"></span>
        <span class="seven"></span>
    </div>
    <div class="consulting_banner__mainShape">
        <div class="consulting_banner__mainShape__left mainShape">
            <svg width="215" height="192" viewBox="0 0 215 192" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M48.3255 11.6792C52.5349 4.82191 60.0366 0.679505 68.0822 0.769665L152.21 1.7124C160.448 1.80472 168.001 6.31908 171.984 13.5315L211.751 85.5473C215.733 92.7597 215.531 101.556 211.221 108.578L167.207 180.28C162.997 187.137 155.496 191.28 147.45 191.189L63.3223 190.247C55.0838 190.154 47.5311 185.64 43.5485 178.428L3.78176 106.412C-0.200906 99.1994 0.00150624 90.4027 4.31169 83.3811L48.3255 11.6792Z" fill="#00B7C2"/>
            </svg>
        </div>
        <div class="consulting_banner__mainShape__right mainShape">
            <svg width="215" height="192" viewBox="0 0 215 192" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M48.3255 11.6792C52.5349 4.82191 60.0366 0.679505 68.0822 0.769665L152.21 1.7124C160.448 1.80472 168.001 6.31908 171.984 13.5315L211.751 85.5473C215.733 92.7597 215.531 101.556 211.221 108.578L167.207 180.28C162.997 187.137 155.496 191.28 147.45 191.189L63.3223 190.247C55.0838 190.154 47.5311 185.64 43.5485 178.428L3.78176 106.412C-0.200906 99.1994 0.00150624 90.4027 4.31169 83.3811L48.3255 11.6792Z" fill="#00B7C2"/>
            </svg>
        </div>
    </div>
    <div class="container">
        <div class="row gy-5 align-items-center justify-content-center flex-column-reverse flex-xxl-row">
            <div class="col-lg-12">
                <div class="consulting_banner__dotShape">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-8 col-lg-10">
                <div class="consulting_bannerWrapper">
                    <div class="consulting_banner__single">
                        <div class="consulting_banner__single__content">
                            <h2 class="consulting_banner__single__content__title fw-600">  {{$data['title']}}
                                <span class="consulting_banner__single__content__title__span">
                                    <span class="consulting_banner_title_shape">
                                        <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_banner_title_shape.svg')}}" alt="">
                                    </span>
                                </span>
                            </h2>
                            <p class="consulting_banner__single__content__para mt-3"> {{$data['description']}} </p>
                            <div class="btn-wrapper">
                                <a href="{{$data['button_url']}}" class="consulting_cmn_btn btn_bg_1 radius-10 mt-4 mt-lg-5">{{$data['button_text']}} </a>
                            </div>
                        </div>
                    </div>
                    <div class="consulting_banner_reviewer mt-5">
                        <div class="consulting_banner_reviewer__flex d-flex">
                            <div class="consulting_banner_reviewer__thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_reviewer1.jpg')}}" alt="reviewer">
                                </a>
                            </div>
                            <div class="consulting_banner_reviewer__thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_reviewer2.jpg')}}" alt="reviewer">
                                </a>
                            </div>
                            <div class="consulting_banner_reviewer__thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_reviewer3.jpg')}}" alt="reviewer">
                                </a>
                            </div>
                            <div class="consulting_banner_reviewer__thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_reviewer4.jpg')}}" alt="reviewer">
                                </a>
                            </div>
                            <div class="consulting_banner_reviewer__thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_reviewer5.jpg')}}" alt="reviewer">
                                </a>
                            </div>
                        </div>
                        <h4 class="consulting_banner_reviewer__title">
                            <a href="{{$data['button_url']}}">{{$data['bottom_title']}}</a>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-xxl-6 col-xl-6 col-lg-7">
                <div class="consulting_banner__right">
                    <div class="consulting_banner__right__thumb">
                        <div class="consulting_banner__right__thumb__one consulting_mask">
{{--                            <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_banner1.png')}}" alt="bannerImg">--}}
                            {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                        </div>
                        <div class="consulting_banner__right__thumb__two consulting_mask">
{{--                            <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_banner2.png')}}" alt="bannerImg">--}}
                            {!! render_image_markup_by_attachment_id($data['right_image_two']) !!}
                        </div>
                        <div class="consulting_banner__right__thumb__three consulting_mask">
{{--                            <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_banner3.png')}}" alt="bannerImg">--}}
                            {!! render_image_markup_by_attachment_id($data['right_image_three']) !!}
                        </div>
                        <div class="consulting_banner__right__thumb__four consulting_mask">
{{--                            <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_banner4.png')}}" alt="bannerImg">--}}
                            {!! render_image_markup_by_attachment_id($data['right_image_four']) !!}
                        </div>
                        <div class="consulting_banner__right__thumb__five consulting_mask">
{{--                            <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_banner5.png')}}" alt="bannerImg">--}}
                            {!! render_image_markup_by_attachment_id($data['right_image_five']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
