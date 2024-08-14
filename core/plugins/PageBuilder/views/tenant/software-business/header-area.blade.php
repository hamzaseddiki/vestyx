
@include('tenant.frontend.partials.topbar')
<header class="header-style-01">

    <div class="softwareFirm_header__bg">
        <div class="softwareFirm_header__shapes">
            <img src="{{global_asset('assets/tenant/frontend/themes/img/software-business/banner/software_banner_shape.png')}}" alt="">
        </div>
        <!-- Menu area Starts -->
        <nav class="navbar softwareFirm_nav navbar-area navbar-padding navbar-expand-lg">
            <div class="container nav-container">
                <div class="responsive-mobile-menu">
                    <div class="logo-wrapper">
                        <a href="{{url('/')}}" class="logo">
                            {!! render_image_markup_by_attachment_id(get_static_option('site_logo'),'logo') !!}
                        </a>
                    </div>
                    <a href="javascript:void(0)" class="click-nav-right-icon">
                        <i class="fa-solid fa-ellipsis-v"></i>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#book_point_menu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                @php
                    $primary_menu = \App\Models\Menu::where(['status' => 'default'])->first();
                    $primary_menu = !empty($primary_menu) ? $primary_menu->id : '';
                @endphp
                <div class="collapse navbar-collapse" id="book_point_menu">
                    <ul class="navbar-nav">
                        {!! render_frontend_menu($primary_menu) !!}
                    </ul>
                </div>
                <div class="navbar-right-content show-nav-content">
                    <div class="single-right-content">
                        <div class="navbar-right-btn">
                            <a href="{{ get_static_option('software_business_top_contact_button_'.get_user_lang().'_url') }}" class="softwareFirm_cmn_btn btn_bg_1 softwareFirm_cmn_btn-small radius-10">
                                {{ get_static_option('software_business_top_contact_button_'.get_user_lang().'_text') ?? __('Contact') }}<i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="softwareFirm_banner_area softwareFirm_banner__padding" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
            <div class="container">
                <div class="softwareFirm_banner__Inner softwareFirm-bg-secondary radius-10">

                    <div class="softwareFirm_banner__Inner__dotShape">
                        <span></span>
                        <span></span>
                    </div>

                    <div class="row gy-5 align-items-center justify-content-between flex-column-reverse flex-lg-row">
                        <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-9">
                            <div class="softwareFirm_banner white">
                                <div class="softwareFirm_banner__content">
                                    <h2 class="softwareFirm_banner__title">{{$data['title']}}</h2>
                                    <p class="softwareFirm_banner__para mt-3">{{$data['description']}}</p>
                                    <div class="btn-wrapper btn_flex mt-4">
                                        <a href="{{$data['left_button_url']}}" class="softwareFirm_cmn_btn btn_bg_1 radius-10">{{$data['left_button_text']}}</a>
                                        <a href="{{$data['right_button_url']}}" class="softwareFirm_cmn_btn btn_outline_1 color_one radius-10">{{$data['right_button_text']}}</a>
                                    </div>
                                    <div class="softwareFirm_banner__reviewer mt-5">
                                        <div class="softwareFirm_banner__reviewer__flex">
                                            <div class="softwareFirm_banner__reviewer__thumb">
                                                <a href="javascript:void(0)">
                                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/software-business/banner/software_reviewer1.jpg')}}" alt="reviewer">
                                                </a>
                                            </div>
                                            <div class="softwareFirm_banner__reviewer__thumb">
                                                <a href="javascript:void(0)">
                                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/software-business/banner/software_reviewer2.jpg')}}" alt="reviewer">
                                                </a>
                                            </div>
                                            <div class="softwareFirm_banner__reviewer__thumb">
                                                <a href="javascript:void(0)">
                                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/software-business/banner/software_reviewer3.jpg')}}" alt="reviewer">
                                                </a>
                                            </div>
                                            <div class="softwareFirm_banner__reviewer__thumb">
                                                <a href="javascript:void(0)">
                                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/software-business/banner/software_reviewer4.jpg')}}" alt="reviewer">
                                                </a>
                                            </div>
                                            <div class="softwareFirm_banner__reviewer__thumb">
                                                <a href="javascript:void(0)">
                                                    <img src="{{global_asset('assets/tenant/frontend/themes/img/software-business/banner/software_reviewer5.jpg')}}" alt="reviewer">
                                                </a>
                                            </div>
                                        </div>
                                        <p class="softwareFirm_banner__reviewer__para mt-3"><a href="javascript:void(0)">{{$data['bottom_text']}}</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-9">
                            <div class="softwareFirm_banner__right center-text">
                                <div class="softwareFirm_banner__right__thumb">
                                    <div class="softwareFirm_banner__right__thumb__main">
                                        {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                                    </div>
                                    <div class="softwareFirm_banner__right__thumb__shape">
                                        <svg width="715" height="710" viewBox="0 0 715 710" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <path d="M3.53683 118.973C-6.16516 99.2799 10.0811 76.3513 31.8455 79.2256C67.9854 83.9985 116.134 90.0881 153.117 93.7879C184.034 96.8809 201.301 100.34 232.368 100.827C275.19 101.498 299.507 99.2163 341.638 91.529C381.356 84.2818 402.873 76.4024 441.261 63.8951C487.771 48.742 547.271 22.2747 588.291 3.09489C608.36 -6.28898 631.312 10.8008 627.993 32.7055C622.625 68.1278 616.208 114.719 613.528 150.766C610.702 188.769 608.512 210.173 609.559 248.267C610.413 279.352 611.01 296.954 616.167 327.62C620.101 351.015 624.029 363.814 630.033 386.765C635.072 406.027 637.94 416.832 643.993 435.8C663.656 497.405 684.578 530.574 710.258 587.554C711.742 590.846 712.864 594.691 713.712 598.699C717.145 614.932 701.523 627.371 685.16 624.625C637.184 616.573 543.608 602.705 476.309 604.276C425.891 605.454 397.099 605.993 347.729 616.288C299.584 626.328 273.704 637.173 227.998 655.331C194.858 668.497 148.097 690.374 114.422 706.548C95.5915 715.592 74.2675 700.95 78.2434 680.441C79.6109 673.387 81.2418 666.232 83.1725 659.528C98.7812 605.328 99.1205 581.745 101.99 531.16C105.003 478.048 103.899 447.778 96.996 395.03C92.3018 359.16 88.672 339.033 79.1711 304.126C68.1075 263.478 58.2398 241.741 42.2074 202.784C31.6033 177.018 16.3133 144.907 3.53683 118.973Z" fill="url(#pattern0)"/>
                                            <path d="M3.53683 118.973C-6.16516 99.2799 10.0811 76.3513 31.8455 79.2256C67.9854 83.9985 116.134 90.0881 153.117 93.7879C184.034 96.8809 201.301 100.34 232.368 100.827C275.19 101.498 299.507 99.2163 341.638 91.529C381.356 84.2818 402.873 76.4024 441.261 63.8951C487.771 48.742 547.271 22.2747 588.291 3.09489C608.36 -6.28898 631.312 10.8008 627.993 32.7055C622.625 68.1278 616.208 114.719 613.528 150.766C610.702 188.769 608.512 210.173 609.559 248.267C610.413 279.352 611.01 296.954 616.167 327.62C620.101 351.015 624.029 363.814 630.033 386.765C635.072 406.027 637.94 416.832 643.993 435.8C663.656 497.405 684.578 530.574 710.258 587.554C711.742 590.846 712.864 594.691 713.712 598.699C717.145 614.932 701.523 627.371 685.16 624.625C637.184 616.573 543.608 602.705 476.309 604.276C425.891 605.454 397.099 605.993 347.729 616.288C299.584 626.328 273.704 637.173 227.998 655.331C194.858 668.497 148.097 690.374 114.422 706.548C95.5915 715.592 74.2675 700.95 78.2434 680.441C79.6109 673.387 81.2418 666.232 83.1725 659.528C98.7812 605.328 99.1205 581.745 101.99 531.16C105.003 478.048 103.899 447.778 96.996 395.03C92.3018 359.16 88.672 339.033 79.1711 304.126C68.1075 263.478 58.2398 241.741 42.2074 202.784C31.6033 177.018 16.3133 144.907 3.53683 118.973Z" fill="#FF805D"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>
<div class="search-suggestion-overlay"></div>



