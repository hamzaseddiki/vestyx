<section class="testimonialArea top-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <!-- Section Tittle -->
        <div class="row">
            <div class="col-xl-8 col-lg-10 col-md-10 col-sm-10">
                <div class="section-tittle mb-40">
                    {!!  get_modified_title_knowledgebase($data['title']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="global-slick-init slider-inner-margin arrowStyleFour" data-rtl="{{get_slider_language_deriection()}}" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 3}},{"breakpoint": 1600,"settings": {"slidesToShow": 3}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 991,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 2}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>
                    @foreach($data['testimonial'] as $item)
                        <div class="singleTestimonial">
                            <!-- Client -->
                            <div class="testimonialClient">
                                <!-- Clients -->
                                <div class="clients">
                                    <div class="clientImg">
                                        {!! render_image_markup_by_attachment_id($item->image) !!}
                                    </div>
                                    <div class="clientText">
                                        <span class="clientName">{{$item->getTranslation('name',get_user_lang())}}</span>
                                        <p class="clinetDisCrip">{{ $item->getTranslation('designation',get_user_lang()) }}</p>
                                    </div>
                                </div>
                                <!-- Client feedback -->
                                <div class="feedback">
                                    <ul class="rattingList">
                                        <li class="listItems"><i class="las la-star icon"></i></li>
                                        <li class="listItems"><i class="las la-star icon"></i></li>
                                        <li class="listItems"><i class="las la-star icon"></i></li>
                                        <li class="listItems"><i class="las la-star icon"></i></li>
                                        <li class="listItems"><i class="las la-star icon"></i></li>
                                    </ul>
                                    <div class="dates">
                                        <span class="dateTime">{{ $item->created_at?->format('D m, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="testiPera">
                                <p class="pera">{!! \Illuminate\Support\Str::words(purify_html($item->getTranslation('description',get_user_lang())),20) !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
