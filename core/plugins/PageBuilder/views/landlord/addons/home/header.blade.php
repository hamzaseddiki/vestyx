<!-- Hero Area S t a r t -->
<div class="sliderArea plr {{ !empty($data['default_bg']) ? 'sectionBg1' : '' }}" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}" style="background: {{$data['bg_color']}}">
    <div class="slider-active">
        <div class="single-slider heroPadding d-flex align-items-center">
            <div class="container-fluid ">
                <div class="row justify-content-between align-items-center">
                    <div class="col-xxl-6 col-xl-7 col-lg-7 ">
                        <div class="heroCaption">
                            @php
                                $shape_img = null;
                                $condition_of_extra = get_static_option('section_title_extra_design_status');
                                  $title_class_condition = !empty($condition_of_extra) ? 'tittle' : 'title';
                                if(!empty($condition_of_extra)){
                                    $shape_img =  render_image_markup_by_attachment_id($data['shape_image'],'TittleShape');
                                }

                                    $final_title = '';
                                   if (str_contains($data['title'], '{h}') && str_contains($data['title'], '{/h}'))
                                   {
                                       $text = explode('{h}',$data['title']);
                                       $highlighted_word = explode('{/h}', $text[1])[0];

                                       $highlighted_text = '<span class="tittleColor slideEffect">'. $highlighted_word .'</span>';
                                       $final_title = '<h1 class="'.$title_class_condition.'" data-animation="fadeInUp" data-delay="0.1s">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $data['title']). $shape_img.'</h2>';
                                   } else {
                                       $final_title = '<h1 class="'.$title_class_condition.'" data-animation="fadeInUp" data-delay="0.1s">'. $data['title'] .$shape_img.'</h2>';
                                   }
                            @endphp
                            {!! $final_title !!}
                            <p class="pera" data-animation="fadeInUp" data-delay="0.3s">{{$data['subtitle']}}</p>

                            <div class="btn-wrapper btn-rtl">
                                <a href="{{$data['button_url']}}" class="cmn-btn2 hero-btn mr-15 mb-10 wow fadeInLeft" data-wow-delay="0.2s">{{$data['button_text']}}</a>
                                <span class="offerDate wow fadeInRight" data-wow-delay="0.2s">{{$data['right_text']}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-5 col-xl-5 col-lg-5">
                        <div class="hero-man d-none d-lg-block f-right" >
							<dotlottie-player src="https://lottie.host/10f4c554-d80e-4a2e-8894-0d480511b973/yYZPpuZk9F.json" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></dotlottie-player>
                           <!-- {!! render_image_markup_by_attachment_id($data['right_image'] , 'tilt-effect lazy', '','fadeInRight','0.2s') !!}-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End-of Hero  -->
