@php
    $end_date = $data["campaign"]->end_date;
@endphp
<section class="offerCartArea wow fadeInUp" data-wow-delay="0.0s">
    <div class="container">
        <div class="row">
            <div class="col-xxl-12">
                <div class="offerCartCap sectionBg1">
                    <div class="row align-items-end justify-content-between">
                        <div class="col-xl-5 col-lg-5 col-md-6">
                            <div class="offerDiscription">
                                <div class="offerTittle mb-30">
                                    <h3 class="tittle wow fadeInUp" data-wow-delay="0.0s">
                                        {!! get_string_line_breaker($data['title'],2) !!}
                                    </h3>
                                </div>
                                <!-- Timer -->
                                <div class="dateTimmerGlobal whiteStyle mb-40  wow fadeInUp flash-countdown" data-date="{{ $end_date }}" data-wow-delay="0.1s">
                                    <div class="single">
                                        <div class="cap">
                                            <span class="time counter-days"></span>
                                            <p class="cap">{{__('Days')}}</p>
                                        </div>
                                    </div>
                                    <div class="single">
                                        <span class="time counter-hours"></span>
                                        <p class="cap">{{__('Hours')}}</p>
                                    </div>
                                    <div class="single">
                                        <span class="time counter-minutes"></span>
                                        <p class="cap">{{__('Mins')}}</p>
                                    </div>
                                    <div class="single">
                                        <span class="time counter-seconds"></span>
                                        <p class="cap">{{__('Secs')}}</p>
                                    </div>
                                </div>
                                <!-- /E n d -->

                                <div class="btn-wrapper mt-20 wow fadeInUp" data-wow-delay="0.3s" >
                                    <a href="#!" target="_blank" class="cmn-btn2 hero-btn">{{$data['button_text']}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-6 position-relative">
                            <div class="offerCart f-right mr-10">
                                {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                            </div>

                            <!-- Shape 01-->
                            <div class="offerCartShape offerCartShape1">
                                {!! render_image_markup_by_attachment_id($data['offer_image'],'bouncingAnimation') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
