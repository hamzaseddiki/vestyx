
<div class="portfolio_banner_area portfolio_banner__padding portfolio_section_bg portfolio_banner__overlay home_portfolio"
data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="portfolio_banner__shapes">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/portfolio/banner/portfolio_banner_waveCircle.svg')}}" alt="">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/portfolio/banner/portfolio_banner_plusShape.svg')}}" alt="">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/portfolio/banner/portfolio_banner_plusShape.svg')}}" alt="">
    </div>
    <div class="container">
        <div class="row gy-5 align-items-center justify-content-between flex-column-reverse flex-lg-row">
            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-9">
                <div class="portfolio_banner white">
                    <div class="portfolio_banner__content">
                        <span class="portfolio_banner__subtitle">{{$data['top_title']}}</span>

                        @php
                            $main_title = $data['title'];
                            $explode = explode(' ',$main_title) ?? [];
                            $first_word = array_slice($explode,0,1) ?? [];
                            $last_words = array_diff($explode,$first_word) ?? [];
                        @endphp

                        <h2 class="portfolio_banner__title">{{ current($first_word) ?? '' }}
                            <span class="portfolio_banner__title__style"> {{ implode(' ',$last_words) ?? '' }}
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/portfolio/banner/portfolio_title_shape.png')}}" alt="">
                            </span>
                        </h2>

                        <p class="portfolio_banner__para mt-3">{{$data['description']}}</p>

                        <div class="btn-wrapper mt-4">
                            <a href="{{$data['button_url']}}" class="portfolio_cmn_btn btn_bg_1 radius-30">{{$data['button_text']}}</a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-9">
                <div class="portfolio_banner__right">
                    <div class="portfolio_banner__thumb">
                        <div class="portfolio_banner__thumb__main">
                            {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                        </div>
                        <div class="portfolio_banner__thumb__shape">
                            <img src="{{global_asset('assets/tenant/frontend/themes/img/portfolio/banner/portfolio_banner_wave.svg')}}" alt="thumbText">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-4">
            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $ti)
                @php
                    $title = $ti ?? '';
                    $number = $data['repeater_data']['repeater_number_'.get_user_lang()][$key] ?? '';
                    $extra = $data['repeater_data']['repeater_extra_'.get_user_lang()][$key] ?? '';
                @endphp
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="portfolio_counter white desktop-center">
                        <div class="portfolio_counter__contents">
                            <div class="portfolio_counter__count">
                                <span class="odometer color-heading" data-odometer-final="{{$number}}"></span>
                                <span class="portfolio_counter__count__title color-heading">{{$extra}}</span>
                            </div>
                            <p class="portfolio_counter__para mt-1 mt-md-3"> {{$title}} </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
