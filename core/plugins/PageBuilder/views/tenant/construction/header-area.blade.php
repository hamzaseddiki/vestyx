
@php
    $primary_menu = \App\Models\Menu::where(['status' => 'default'])->first();
@endphp

<header class="header-style-01">
    <div class="construction_header__bg" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
        <!-- top bar -->
        @include('tenant.frontend.partials.topbar')
        <!-- Menu area Starts -->
        <nav class="navbar construction_nav white-nav navbar-area navbar-padding navbar-expand-lg">
            <div class="container nav-container">
                <div class="responsive-mobile-menu">
                    <div class="logo-wrapper">
                        <a href="{{url('/')}}" class="logo">
                            {!! render_image_markup_by_attachment_id(get_static_option('site_logo'),'logo') !!}
                        </a>
                    </div>
                    <a href="javascript:void(0)" class="click-nav-right-icon">
                        <i class="las la-ellipsis-v"></i>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#book_point_menu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="book_point_menu">
                    <ul class="navbar-nav">
                        {!! render_frontend_menu($primary_menu) !!}
                    </ul>
                </div>
                <div class="navbar-right-content show-nav-content">
                    <div class="single-right-content">
                        <div class="navbar-right-btn">
                            <a href="{{ get_static_option('construction_top_contact_button_'.get_user_lang().'_url') }}" class="construction_cmn_btn btn_bg_1 construction_cmn_btn-small radius-10 ">
                                {{ get_static_option('construction_top_contact_button_'.get_user_lang().'_text') ?? __('Contact Us') }} <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>


<div class="construction_banner_area construction_banner__padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row gy-5 align-items-center justify-content-between flex-column-reverse flex-lg-row">
            <div class="col-xxl-6 col-xl-7 col-lg-7 col-md-9">
                <div class="construction_bannerWrapper white">
                    <div class="construction_bannerWrapper__content">
                        <h2 class="construction_bannerWrapper__title">
                            <span class="construction_bannerWrapper__title__shape white">
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/construction/banner/construction_title_shape.svg')}}" alt="titleShape">
                            </span>{{ $data['title'] }}</h2>
                        <p class="construction_bannerWrapper__para mt-3">{{$data['description']}}</p>
                        <div class="btn-wrapper">
                            <a href="{{$data['button_url']}}" class="construction_cmn_btn btn_bg_1 radius-10 mt-4 mt-lg-5">
                                {{$data['button_text']}} <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-5 col-lg-5 col-md-9">
                <div class="construction_banner__right center-text">
                    <div class="construction_banner__right__thumb">

                        {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                        <div class="construction_banner__right__thumb__shapeWrapper">
                            <span class="construction_banner__right__thumb__shape" style="background-image: url({{global_asset('assets/tenant/frontend/themes/img/construction/banner/construction_banner_wave.svg')}});"></span>
                            <span class="construction_banner__right__thumb__dotShape">
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/construction/banner/construction_banner_dotShape.png')}}" alt="dotShape">
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/construction/banner/construction_banner_dotShape.png')}}" alt="dotShape">
                            </span>
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

