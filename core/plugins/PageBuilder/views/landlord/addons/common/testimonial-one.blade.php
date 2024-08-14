@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();

    $lang_direction = get_user_lang_direction();
    $land_direction_condition = $lang_direction == 1 ? 'true' : 'false';
@endphp

<section class="testimonialArea" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container" >
        <div class="testimonial-wrapper sectionBg1 wow fadeInUp" data-wow-delay="0.1s">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-10 col-sm-10">
                    <div class="section-tittle text-center mb-25 wow fadeInUp" data-wow-delay="0.0s">
                        {!! get_landlord_modified_title($data['title']) !!}
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-7 col-sm-12">
                    <div class="global-slick-init DotStyleTwo" data-infinite="true" data-arrows="false"
                         data-dots="true" data-slidesToShow="1" data-swipeToSlide="true" data-autoplay="true" data-rtl="{{$land_direction_condition}}"
                         data-autoplaySpeed="2500">
                        <!-- Single Testimonial -->
                        @foreach($data['testimonial'] as $item)
                            <div class="single-testimonial text-center">
                            <!-- founder -->
                            <div class="testimonial-founder d-flex align-items-center justify-content-center">
                                <div class="founder-text text-center">
                                    <div class="founder-img wow fadeInUp" data-wow-delay="0.0s">
                                        {!! render_image_markup_by_attachment_id($item->image) !!}
                                    </div>
                                    <span class="name wow fadeInUp" data-wow-delay="0.1s">{{$item->getTranslation('name',$current_lang)}}</span>
                                    <p class="pera wow fadeInUp" data-wow-delay="0.2s">{{$item->getTranslation('designation',$current_lang)}}</p>
                                    <div class="rating wow fadeInUp" data-wow-delay="0.3s">
                                        <ul>
                                            <li><i class="las la-star"></i></li>
                                            <li><i class="las la-star"></i></li>
                                            <li><i class="las la-star"></i></li>
                                            <li><i class="las la-star"></i></li>
                                            <li><i class="las la-star"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-caption">
                                <p class="desCription wow fadeInUp" data-wow-delay="0.4s">{!! $item->getTranslation('description',$current_lang) !!}</p>
                            </div>
                        </div>
                        @endforeach
                        <!-- Single Testimonial -->

                    </div>
                </div>
            </div>
            <!-- Shape 01-->
            <div class="shapeTestimonial shapeTestimonial1" data-animation="fadeInLeft" data-delay="0.8s">
                {!! render_image_markup_by_attachment_id($data['left_image'],'heartbeat3') !!}
            </div>
            <!-- Shape 02-->
            <div class="shapeTestimonial shapeTestimonial2 " data-animation="fadeInDown" data-delay="0.7s">
                {!! render_image_markup_by_attachment_id($data['right_image'],'heartbeat3') !!}
            </div>

            <!-- Shape 03-->
            <div class=" shapeTestimonial shapeTestimonial3" data-animation="fadeInRight" data-delay="0.6s">
                <img src="{{asset('assets/landlord/frontend/img/icon/star1.svg')}}" alt="image" class="bounce-animate">
            </div>
            <!-- Shape 04-->
            <div class=" shapeTestimonial shapeTestimonial4" data-animation="fadeInRight" data-delay="0.6s">
                <img src="{{asset('assets/landlord/frontend/img/icon/star2.svg')}}" alt="image" class="rotateme">
            </div>
            <!-- Shape 05-->
            <div class=" shapeTestimonial shapeTestimonial5" data-animation="fadeInRight" data-delay="0.6s">
                <img src="{{asset('assets/landlord/frontend/img/icon/star3.svg')}}" alt="image" class="running">
            </div>
            <!-- Shape 06-->
            <div class=" shapeTestimonial shapeTestimonial6" data-animation="fadeInRight" data-delay="0.6s">
                <img src="{{asset('assets/landlord/frontend/img/icon/star4.svg')}}" alt="image" class="bounce-animate">
            </div>
        </div>
    </div>
</section>
<!-- End-of Testimonial -->
