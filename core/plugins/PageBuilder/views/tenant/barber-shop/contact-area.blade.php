<section class="barberShop_discount_area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="barberShop_discount center-text barberShop-bg-main padding-top-100 padding-bottom-100">
                    <div class="barberShop_discount__shapes">
                        {!! render_image_markup_by_attachment_id($data['top_shape_text_image']) !!}
                        {!! render_image_markup_by_attachment_id($data['short_bottom_image']) !!}
                    </div>
                    <div class="barberShop_discount__single">
                        <div class="barberShop_sectionTitle">
                            {!! get_modified_title_barber_two($data['title']) !!}
                        </div>
                        <p class="barberShop_discount__single__para mt-3">{{$data['subtitle']}}</p>
                        <div class="btn-wrapper mt-4">
                            <a href="{{$data['button_url']}}" class="barberShop_cmn_btn btn_bg_1">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
