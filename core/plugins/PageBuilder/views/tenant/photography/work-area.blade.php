
<section class="photography_explore_area padding-top-100 padding-bottom-50" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="photography_explore__shapes">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/photography/explore/photography_explore_camera.png')}}" alt="exploreCamera">
    </div>
    <div class="container">
        <div class="photography_sectionTitle">
            {!! get_modified_title_photography($data['title']) !!}
        </div>

        <div class="row imagesloaded masonry_grid g-4 mt-4">
            @foreach($data['repeater_data']['repeater_image_'] ?? [] as $key => $img)
                @php
                    $image = $img ?? '';
                    $image_url = $data['repeater_data']['repeater_image_url_'][$key] ?? '';
                    $display_style = $data['repeater_data']['repeater_style_'][$key] ?? '';

                    $display_condition = 'col-md-6';
                    if($display_style == 'vertical'){
                        $display_condition = 'col-md-6';
                    }elseif ($display_style == 'horizontal'){
                        $display_condition = 'col-md-6';
                    }else if ($display_style == 'grid'){
                        $display_condition = 'col-sm-4';
                    }
                @endphp

                @if($display_style != 'grid')
                <div class="{{$display_condition}} masonry_grid_item">
                    <div class="photography_explore radius-10">
                        <div class="photography_explore__thumb">
                            <a href="{{$image_url}}">
                                {!! render_image_markup_by_attachment_id($image) !!}
                            </a>
                        </div>
                    </div>
                </div>
                @endif


            @endforeach


              <div class="col-md-6 masonry_grid_item">
                 <div class="row g-4">
                @foreach($data['repeater_data']['repeater_image_'] ?? [] as $key => $img)
                         @php
                             $image = $img ?? '';
                             $image_url = $data['repeater_data']['repeater_image_url_'][$key] ?? '';
                             $display_style = $data['repeater_data']['repeater_style_'][$key] ?? '';

                             $display_condition = 'col-md-6';
                             if($display_style == 'vertical'){
                                 $display_condition = 'col-md-6';
                             }elseif ($display_style == 'horizontal'){
                                 $display_condition = 'col-md-6';
                             }else if ($display_style == 'grid'){
                                 $display_condition = 'col-sm-4';
                             }
                         @endphp

                         @if($display_style == 'grid')
                            <div class="col-sm-6">
                                <div class="photography_explore radius-10">
                                    <div class="photography_explore__thumb">
                                        <a href="">
                                            {!! render_image_markup_by_attachment_id($image) !!}
                                        </a>
                                    </div>
                                </div>
                            </div>
                         @endif
                @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="btn-wrapper center-text mt-4 mt-lg-5">
                    <a href="{{$data['button_url']}}" class="photography_cmn_btn btn_bg_1 radius-30">{{$data['button_text']}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
