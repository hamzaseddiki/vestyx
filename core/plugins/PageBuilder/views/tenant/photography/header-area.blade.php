<div class="photography_banner_area photography_banner__padding photography-main-gradient">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row gy-5 align-items-center justify-content-between flex-column-reverse flex-lg-row">
            <div class="col-xxl-7 col-xl-7 col-lg-6 col-md-9">
                <div class="photography_banner">
                    <div class="photography_banner__shape">
                        <svg width="86" height="86" viewBox="0 0 86 86" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M42.8539 0.742188C42.8539 0.742188 40.4824 25.8789 50.3763 35.7729C60.2703 45.6669 85.4071 43.2953 85.4071 43.2953C85.4071 43.2953 60.2703 40.9238 50.3763 50.8178C40.4824 60.7117 42.8539 85.8485 42.8539 85.8485C42.8539 85.8485 45.2255 60.7117 35.3315 50.8178C25.4375 40.9238 0.300781 43.2953 0.300781 43.2953C0.300781 43.2953 25.4375 45.6669 35.3315 35.7729C45.2255 25.8789 42.8539 0.742188 42.8539 0.742188Z" fill="#FF7A03"/>
                        </svg>
                    </div>
                    <div class="photography_banner__content">

                        @php
                            $main_title = $data['title'] ?? '';
                            $explode = explode(' ',$main_title) ?? [];

                            $first_word = current($explode) ?? '';
                            $second_word = $explode[1] ?? '';
                            $third_word = $explode[2] ?? '';

                            $combined_first_three = array_slice($explode,0,3) ?? [];
                            $last_words = array_diff($explode,$combined_first_three) ?? [];
                        @endphp

                        <h2 class="photography_banner__title"><span class="photography_banner__title__capture">{{$first_word}}
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/photography/banner/photography_banner_camera.png')}}" alt=""></span>
                            <span class="photography_banner__title__icon"> {{$second_word}} <i class="fa-solid fa-arrow-right-long"></i>{{$third_word}}</span>

                            <div class="photography_banner__title__author">
                                <div class="photography_banner__title__author__thumb">
                                    <div class="photography_banner__title__author__thumb__item">
                                        <a href="javascript:void(0)"><img src="{{global_asset('assets/tenant/frontend/themes/img/photography/banner/photography_author1.jpg')}}" alt="authorImg"></a>
                                    </div>
                                    <div class="photography_banner__title__author__thumb__item">
                                        <a href="javascript:void(0)"><img src="{{global_asset('assets/tenant/frontend/themes/img/photography/banner/photography_author2.jpg')}}" alt="authorImg"></a>
                                    </div>
                                    <div class="photography_banner__title__author__thumb__item">
                                        <a href="javascript:void(0)"><img src="{{global_asset('assets/tenant/frontend/themes/img/photography/banner/photography_author3.jpg')}}" alt="authorImg"></a>
                                    </div>
                                    <div class="photography_banner__title__author__thumb__item">
                                        <a href="javascript:void(0)"><img src="{{global_asset('assets/tenant/frontend/themes/img/photography/banner/photography_author4.jpg')}}" alt="authorImg"></a>
                                    </div>
                                </div>
                                {{!empty($last_words) ? implode(' ',$last_words) : '' }}
                            </div>
                        </h2>
                        <p class="photography_banner__para mt-4">{{$data['description']}}</p>
                        <div class="btn-wrapper btn_flex mt-4 mt-lg-5">
                            <a href="{{$data['left_button_url']}}" class="photography_cmn_btn btn_bg_1 radius-30">{{$data['left_button_text']}}</a>
                            <a href="{{ $data['video_url'] }}" class="open_popup photography_banner__popup">
                                <i class="fa-solid fa-play"></i> {{$data['video_button_text']}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-5 col-xl-5 col-lg-6 col-md-9">
                <div class="global-slick-init project-slider dot-style-one slider-inner-margin" data-rtl="{{ get_slider_language_deriection() }}" data-appendArrows=".append_banner_nav" data-arrows="true" data-infinite="true" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right-long"></i></div>'>

                    @foreach($data['repeater_data']['repeater_image_'] ?? [] as $key=> $img)
                        @php
                            $image = $img ?? '';
                        @endphp
                        <div class="photography_banner__right">
                            <div class="photography_banner__thumb">
                                <div class="photography_banner__thumb__main">
                                    {!! render_image_markup_by_attachment_id($image) !!}
                                </div>
                                <div class="photography_banner__thumb__shape">
                                    <svg width="86" height="86" viewBox="0 0 86 86" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M42.8539 0.742188C42.8539 0.742188 40.4824 25.8789 50.3763 35.7729C60.2703 45.6669 85.4071 43.2953 85.4071 43.2953C85.4071 43.2953 60.2703 40.9238 50.3763 50.8178C40.4824 60.7117 42.8539 85.8485 42.8539 85.8485C42.8539 85.8485 45.2255 60.7117 35.3315 50.8178C25.4375 40.9238 0.300781 43.2953 0.300781 43.2953C0.300781 43.2953 25.4375 45.6669 35.3315 35.7729C45.2255 25.8789 42.8539 0.742188 42.8539 0.742188Z" fill="#FF7A03"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="append_banner_nav"></div>
            </div>
        </div>
    </div>
</div>

