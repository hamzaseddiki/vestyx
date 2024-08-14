<section class="wedding_activities_area position-relative padding-top-100 padding-bottom-50" data-padding-top="{{$data['padding_top']}}"
         data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="gradient_bg">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="wedding_shape">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/need/wedding_hearts.png')}}" alt="">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/need/wedding_flower1.png')}}" alt="">
    </div>
    <div class="container">
        <div class="wedding_sectionTitle">
            <h2 class="title">{{$data['title']}}</h2>
        </div>
        <div class="row imagesloaded masonry_grid g-4 mt-4">

            @foreach($data['repeater_data']['repeater_image_'] ?? [] as $key => $img)
                @php
                    $image = $img ?? '';
                    $image_url = $data['repeater_data']['repeater_image_url_'][$key] ?? '';
                    $display_style = $data['repeater_data']['repeater_style_'][$key] ?? '';

                    $display_condition = 'col-sm-4';
                    if($display_style == 'vertical'){
                        $display_condition = 'col-sm-4';
                    }elseif ($display_style == 'horizontal'){
                        $display_condition = 'col-sm-8';
                    }else if ($display_style == 'grid'){
                        $display_condition = 'col-sm-4';
                    }
                @endphp

                <div class="{{$display_condition}} masonry_grid_item">
                    <div class="wedding__activities radius-10">
                        <div class="wedding__activities__thumb">
                            <a href="{{$image_url}}">
                                {!! render_image_markup_by_attachment_id($image) !!}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                <div class="btn-wraper center-text mt-4 mt-lg-5">
                    <a href="{{$data['button_url']}}" class="wedding_cmn_btn btn_gradient_main radius-30">{{$data['button_text']}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
