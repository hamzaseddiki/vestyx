
<section class="portfolio_about_area padding-top-50 padding-bottom-100" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row g-5 justify-content-between align-items-center">
            <div class="col-lg-6">
                <div class="portfolio_about__thumb">
                    {!! render_image_markup_by_attachment_id($data['left_image']) !!}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="portfolio_about">
                    <div class="portfolio_sectionTitle text-left">
                        {!! get_modified_title_portfolio($data['title']) !!}
                    </div>
                    <div class="portfolio_about__details mt-4 mt-lg-5">
                        <p class="portfolio_about__details__para mt-4">{!! $data['description'] !!}</p>
                        <div class="btn-wrapper mt-4 mt-lg-5">
                            <a href="{{$data['button_url']}}" class="portfolio_cmn_btn btn_bg_1 radius-30">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
