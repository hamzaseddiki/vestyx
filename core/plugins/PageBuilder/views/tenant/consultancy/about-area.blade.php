<section class="consulting_about_area consulting_section_bg padding-top-100 padding-bottom-100" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="body_border">
        <span class="one"></span>
        <span class="two"></span>
        <span class="three"></span>
        <span class="four"></span>
        <span class="five"></span>
        <span class="six"></span>
        <span class="seven"></span>
    </div>

    <div class="container">
        <div class="row g-5 align-items-center justify-content-between">
            <div class="col-xl-7 col-lg-6">
                <div class="consulting_about__left">
                    <div class="consulting_about__dotShape">
                        <span></span>
                        <span></span>
                    </div>
                    <div class="consulting_about__shape">
                        <div class="consulting_about__shape__one">
                            <svg width="183" height="164" viewBox="0 0 183 164" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <path d="M40.9614 10.1099C44.5479 4.26721 50.9397 0.737692 57.7949 0.81451L129.476 1.61776C136.495 1.69643 142.93 5.54286 146.324 11.6882L180.207 73.0488C183.6 79.1941 183.428 86.6893 179.755 92.672L142.253 153.765C138.667 159.608 132.275 163.137 125.42 163.061L53.7393 162.257C46.7197 162.179 40.2845 158.332 36.8911 152.187L3.00809 90.8263C-0.385314 84.681 -0.212854 77.1858 3.45961 71.2031L40.9614 10.1099Z" fill="url(#pattern0)"/>
                                <path d="M40.9614 10.1099C44.5479 4.26721 50.9397 0.737692 57.7949 0.81451L129.476 1.61776C136.495 1.69643 142.93 5.54286 146.324 11.6882L180.207 73.0488C183.6 79.1941 183.428 86.6893 179.755 92.672L142.253 153.765C138.667 159.608 132.275 163.137 125.42 163.061L53.7393 162.257C46.7197 162.179 40.2845 158.332 36.8911 152.187L3.00809 90.8263C-0.385314 84.681 -0.212854 77.1858 3.45961 71.2031L40.9614 10.1099Z" fill="#FF6B2C"/>
                            </svg>
                        </div>
                        <div class="consulting_about__shape__two">
                            <svg width="183" height="164" viewBox="0 0 183 164" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M45.2226 12.7255C47.8893 8.3812 52.6418 5.75688 57.7389 5.814L129.42 6.61726C134.639 6.67574 139.424 9.53569 141.947 14.1049L175.83 75.4656C178.353 80.0348 178.225 85.6077 175.494 90.0561L137.992 151.149C135.326 155.493 130.573 158.118 125.476 158.061L53.7953 157.257C48.576 157.199 43.7912 154.339 41.2681 149.77L7.38511 88.4091C4.862 83.8399 4.99023 78.267 7.72083 73.8186L45.2226 12.7255Z" stroke="#FFE7C3" stroke-width="10"/>
                            </svg>
                        </div>
                    </div>
                    <div class="consulting_about__thumb">
                        <div class="consulting_about__thumb__left">
                            {!! render_image_markup_by_attachment_id($data['left_image']) !!}
                        </div>
                        <div class="consulting_about__thumb__right">
                            {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-6">
                <div class="consulting_about__right">
                    <div class="consulting_sectionTitle text-left">
                        <span class="subtitle">{!! $data['title'] !!}</span>

                        {!! get_consultancy_subtitle_line_breaker($data['subtitle']) !!}

                        <p class="section_para">{{$data['description']}}</p>
                    </div>
                    <div class="consulting_about__bottom border_top_2">
                        <div class="btn-wrapper flex_btn">
                            <a href="{{$data['left_button_url']}}" class="consulting_cmn_btn btn_bg_1 btn_small radius-10">{{$data['left_button_text']}}</a>
                            <a href="{{$data['right_button_url']}}" class="consulting_cmn_btn btn_outline_1 btn_small color-one radius-10">{{$data['right_button_text']}}</a>
                        </div>
                        <div class="consulting_about__bottom__contents mt-4">
                            <p class="consulting_about__bottom__para border_left">{{$data['bottom_description']}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
