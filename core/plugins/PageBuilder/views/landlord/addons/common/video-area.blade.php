
@php

    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();

@endphp

<section class="aboutArea " data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12">
                <div class="about-caption text-center">
                    <!-- Section Tittle -->
                    <div class="section-tittle section-tittle2 mb-40">
                        {!! get_landlord_modified_title($data['title']) !!}
                    </div>
                </div>
                <!-- about-img -->
                <div class="aboutImg">
                    <div class="logistic-video-area-wrap"> <div class="container">
                           <div class="row"><div class="col-lg-12">
                                  <div class="logistic-video-wrap">
                                      {!! render_image_markup_by_attachment_id($data['bg_image']) !!}
                                       <a href="{{$data['video_url']}}" class="video-play-btn mfp-iframe">
                                           <i class="fas fa-play"></i>
                                       </a>
                                  </div>
                               </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
