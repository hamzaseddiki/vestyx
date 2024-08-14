
<div class="wedding_banner_area position-relative wedding_banner__padding" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="gradient_bg">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="wedding_banner__shapes">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/banner/wedding_flower2.png')}}" alt="">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/banner/wedding_hearts.png')}}" alt="">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/banner/wedding_flower1.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row gy-5 align-items-center justify-content-between flex-column-reverse flex-lg-row">
            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-9">
                <div class="wedding_banner">
                    <div class="wedding_banner__content">
                        @php
                            $original_title = $data['title'] ?? '';
                            $explode = explode(' ',$original_title);

                            $all_words = $explode;
                            $first_words = array_slice($all_words,0,-1);
                            $last_word = array_diff($all_words,$first_words);
                        @endphp

                        <h2 class="wedding_banner__title">{{ !empty($first_words) ? implode( ' ' , $first_words) : '' }} <span class="wedding_banner__title__style">{{ !empty($last_word) ?  implode(' ',$last_word) : '' }}
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/banner/wedding_title_ring.svg')}}" alt="">
                            </span>
                        </h2>
                        <p class="wedding_banner__para mt-3">{{$data['description']}}</p>
                        <div class="btn-wrapper mt-4">
                            <a href="{{$data['button_url']}}" class="wedding_cmn_btn btn_gradient_main radius-30">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-9">
                <div class="wedding_banner__right">
                    <div class="wedding_banner__thumb">
                        <div class="wedding_banner__thumb__main">

                            {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                        </div>
                        <div class="wedding_banner__thumb__small">

                            {!! render_image_markup_by_attachment_id($data['left_image']) !!}
                        </div>
                        <div class="wedding_banner__thumb__shape">
                            <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/banner/wedding_thum_text.png')}}" alt="thumbText">
                        </div>
                        <div class="wedding_banner__thumb__spiderShape">
                            <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/banner/wedding_banner_spiderShape.png')}}" alt="spiderShape">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
