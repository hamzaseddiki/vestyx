@php
    $user_lang = get_user_lang();
@endphp

<section class="testimonial-area  section-padding sectionImg-bg2" data-background="assets/img/gallery/donation-sectionBg1.png">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-5 col-lg-6">
                <div class="testimonial mb-40">
                    <!-- Section Tittle -->
                    <div class="section-tittle mb-10">
                        {!! get_modified_title_tenant($data['title']) !!}
                    </div>
                    <!-- Testimonial Start -->
                    <div class="global-slick-init slider-inner-margin arrowStyleTow" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left-long"></i></div>'
                         data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right-long"></i></div>'>

                        @foreach($data['testimonial'] as $item)
                            <div class="single-testimonial position-relative">
                                <div class="testimonialCaption">
                                    <p class="pera">
                                        <img src="{{global_asset('assets/tenant/frontend/themes/img/icon/donation-quotesLeft.svg')}}" alt="images" class="quotesLeft ">
                                             {!! $item->getTranslation('description',$user_lang) !!}
                                        <img src="{{global_asset('assets/tenant/frontend/themes/img/icon/donation-quotesRihgt.svg')}}" alt="images" class="quotesRihgt ">
                                    </p>
                                </div>
                                <!-- founder -->
                                <div class="testimonial-founder d-flex align-items-center">
                                    <div class="founder-text">
                                        <span>{{$item->getTranslation('name',$user_lang) }}</span>
                                        <p>{{$item->getTranslation('designation',$user_lang)}} </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <!-- video start -->
                <div class="video-area wow fadeInRight" data-wow-delay="0.0s">
                    <div class="video-wrap position-relative">
                        <div class="videoImg">
                            {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                        </div>

                        <div class="video-icon">
                            <a class="popup-video btn-icon" href="{{$data['right_video_url']}}">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- video end -->
            </div>
        </div>
    </div>
</section>
