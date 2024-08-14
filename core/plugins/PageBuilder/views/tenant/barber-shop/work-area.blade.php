
<section class="barberShop_work_area barberShop-bg-main" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="barberShop_work__shapes">
        {!! render_image_markup_by_attachment_id($data['top_short_image']) !!}
        {!! render_image_markup_by_attachment_id($data['bottom_short_image']) !!}
    </div>
    <div class="container">
        <div class="barberShop_sectionTitle">
            {!! get_modified_title_barber_two($data['title']) !!}
        </div>
        <div class="row g-4 mt-4">

            @foreach($data['repeater_data']['repeater_image_'] ?? [] as $key => $img)
                @php
                    $image = $img ?? '';
                    $image_url = $data['repeater_data']['repeater_image_url_'][$key] ?? '';
                    $display_style = $data['repeater_data']['repeater_style_'][$key] ?? '';

                    $display_condition = '';
                    if($display_style == 'vertical'){
                        $display_condition = 'col-md-5';
                    }elseif ($display_style == 'horizontal'){
                        $display_condition = 'col-md-7';
                    }else if ($display_style == 'grid'){
                        $display_condition = 'col-md-3';
                    }else if ($display_style == 'semi-horizontal'){
                        $display_condition = 'col-md-6';
                    }
                @endphp
                 <div class="{{$display_condition}}">
                    <div class="barberShop__work">
                        <div class="barberShop__work__thumb">
                            <a href="{{$image_url}}">
                                {!! render_image_markup_by_attachment_id($image) !!}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="barberShop_shop__btn mt-4 center-text">
            <div class="btn-wrapper">
                <a href="{{$data['button_url']}}" class="barberShop_cmn_btn btn_bg_1">{{$data['button_text']}}</a>
            </div>
        </div>
    </div>
</section>
