
<section class="testimonialArea section-padding sectionBg1">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="testimonial-wrapper sectionBg1 wow fadeInUp" data-wow-delay="0.1s">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                    <div class="section-tittle text-center mb-50">
                        <h2 class="tittle">{{$data['title']}}</h2>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-7 col-sm-12">
                    <div class="global-slick-init DotStyleTwo" data-infinite="false" data-arrows="false" data-dots="true" data-slidesToShow="1" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500">

                        @foreach($data['testimonial'] as $item)
                            <div class="single-testimonial text-center">
                                <div class="testimonial-caption">
                                    <div class="rating wow fadeInUp" data-wow-delay="0.3s">
                                        <ul>
                                            <li><i class="las la-star"></i></li>
                                            <li><i class="las la-star"></i></li>
                                            <li><i class="las la-star"></i></li>
                                            <li><i class="las la-star"></i></li>
                                            <li><i class="las la-star"></i></li>
                                        </ul>
                                    </div>
                                    <p class="desCription wow fadeInUp" data-wow-delay="0.4s">{{$item->getTranslation('description',get_user_lang())}}</p>
                                </div>
                                <!-- founder -->
                                <div class="testimonial-founder d-flex align-items-center justify-content-center">
                                    <div class="founder-text text-center">
                                        <div class="founder-img wow fadeInUp" data-wow-delay="0.0s">
                                            {!! render_image_markup_by_attachment_id($item->image) !!}
                                        </div>
                                        <span class="name wow fadeInUp" data-wow-delay="0.1s">{{$item->getTranslation('name',get_user_lang())}}</span>
                                        <p class="pera wow fadeInUp" data-wow-delay="0.2s">{{$item->getTranslation('designation',get_user_lang())}}</p>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Shape 01-->
            <div class="shapeTestimonial shapeTestimonial1" data-animation="fadeInLeft" data-delay="0.8s">
                <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/job-find-clintLeft.png')}}" alt="" class="heartbeat3">
            </div>
            <!-- Shape 02-->
            <div class="shapeTestimonial shapeTestimonial2 " data-animation="fadeInDown" data-delay="0.7s">
                <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/job-find-clintRight.png')}}" alt="" class="heartbeat3">
            </div>
        </div>
    </div>
</section>
