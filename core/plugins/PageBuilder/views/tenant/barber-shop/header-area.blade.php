<div class="barberShop_banner_area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row gy-5 align-items-center justify-content-between flex-column-reverse flex-lg-row">
            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-9">
                <div class="barberShop_banner">
                    <div class="barberShop_banner__content">

                        {!! get_modified_title_barber($data['title']) !!}

                        <p class="barberShop_banner__para mt-3">{{$data['description']}}</p>
                        <div class="btn-wrapper btn_flex mt-4">
                            <a href="{{$data['button_url']}}" class="barberShop_cmn_btn btn_bg_1">{{$data['button_text']}}</a>
                            <a href="{{$data['video_url']}}" class="barberShop_banner__content__videIcon barberShop_videoPopup"><i class="fa-regular fa-circle-play"></i> <span>{{$data['video_text']}}</span></a>
                        </div>
                        <div class="barberShop_banner__schedule mt-5">
                            <div class="barberShop_banner__schedule__flex">
                                <div class="barberShop_banner__schedule__item">
                                    <h6 class="barberShop_banner__schedule__item__title">{{$data['open_text']}}</h6>
                                    <p class="barberShop_banner__schedule__item__time mt-2">{{ $data['open_time'] }}</p>
                                </div>
                                <div class="barberShop_banner__schedule__item">
                                    <h6 class="barberShop_banner__schedule__item__title">{{$data['close_text']}}</h6>
                                    <p class="barberShop_banner__schedule__item__time mt-2">{{$data['close_time']}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="barberShop_banner__content__shapes">
                            {!! render_image_markup_by_attachment_id($data['left_small_star_image']) !!}
                            {!! render_image_markup_by_attachment_id($data['middle_scissor_image']) !!}
                            {!! render_image_markup_by_attachment_id($data['middle_small_star_image']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-9">
                <div class="barberShop_banner__right text-end">
                    <div class="barberShop_banner__right__thumb">
                        <div class="barberShop_banner__right__thumb__textShape">
                            {!! render_image_markup_by_attachment_id($data['middle_medium_text_star_image']) !!}
                        </div>
                        <div class="barberShop_banner__right__thumb__flex">
                            <div class="barberShop_banner__right__thumb__item">
                                {!! render_image_markup_by_attachment_id($data['right_product_image']) !!}
                                <div class="barberShop_banner__right__thumb__item__shape">
                                    {!! render_image_markup_by_attachment_id($data['right_shape_image']) !!}
                                </div>
                            </div>
                            <div class="barberShop_banner__right__thumb__item">
                                {!! render_image_markup_by_attachment_id($data['right_barber_image']) !!}
                            </div>
                        </div>
                        <div class="barberShop_banner__right__thumb__price">
                            {!! render_image_markup_by_attachment_id($data['right_price_image']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="barberShop_banner__footer">
        <div class="barberShop_banner__footer__inner">
            <div class="barberShop_banner__footer__item">
                <div class="barberShop_banner__footer__item__inner">
                    @foreach($data['barber_shop_banner_bottom_left_repeater']['repeater_title_'.get_user_lang()] ?? [] as $key=> $rep_left_title)
                        @php
                            $rep_left_img = $data['barber_shop_banner_bottom_left_repeater']['repeater_image_'.get_user_lang()][$key] ?? '';
                        @endphp
                        <span>
                            {!! render_image_markup_by_attachment_id($rep_left_img) !!}
                            {{$rep_left_title ?? ''}}
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="barberShop_banner__footer__item">
                <div class="barberShop_banner__footer__item__inner">
                    @foreach($data['barber_shop_banner_bottom_right_repeater']['repeater_title_'.get_user_lang()] ?? [] as $key=> $rep_right_title)
                        @php
                            $rep_right_img = $data['barber_shop_banner_bottom_left_repeater']['repeater_image_'.get_user_lang()][$key] ?? '';
                        @endphp
                        <span>
                            {!! render_image_markup_by_attachment_id($rep_right_img) !!}
                            {{$rep_right_title ?? ''}}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
