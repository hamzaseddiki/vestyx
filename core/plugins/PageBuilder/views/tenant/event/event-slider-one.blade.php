

<section class="eventsArea section-padding2 sectionBg1">
    <div class="container" data-padding-top="{{ $data['padding_top'] }}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row ">
            <div class="col-xl-8 col-lg-10 col-md-10 col-sm-10">
                <div class="section-tittle mb-40">
                    {!! get_modified_title_tenant_event($data['title']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="global-slick-init slider-inner-margin arrowStyleThree" data-rtl="{{ get_slider_language_deriection() }}" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left-long"></i></div>'
                 data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right-long"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 991,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>

                @foreach($data['events'] as $data)
                    <div class="singleEvents">
                        <div class="event-img imgEffect">
                            <a href="{{route('tenant.frontend.event.single',$data->slug)}}">
                                {!! render_image_markup_by_attachment_id($data['image'],'','grid') !!}
                            </a>
                            <div class="img-text">
                                <span class="content">{{ date('d',strtotime($data->date)) }} <span class="month">{{ date('M',strtotime($data->date)) }}</span></span>
                            </div>
                        </div>

                        <div class="eventCaption">
                            <h3><a href="{{route('tenant.frontend.event.single',$data->slug)}}" class="tittle">{{$data->getTranslation('title',get_user_lang())}}</a></h3>
                            <p class="pera">{!! \Illuminate\Support\Str::words(purify_html($data->getTranslation('content',get_user_lang())),28) !!} </p>
                            <ul class="cartTop">
                                <li class="listItmes"><i class="fa-solid fa-location-dot icon"></i> {{$data->venue_location}}</li>
                                <li class="listItmes"><i class="fa-regular fa-clock icon"></i> {{ $data->time }}</li>
                                <li class="listItmes"><i class="fa-solid fa-person-dress icon"></i> {{ !empty($data->available_ticket) ?  ($data->total_ticket - $data->available_ticket) : 0 }} {{__('participate')}}</li>
                            </ul>
                            <!-- Blog Footer -->
                            <div class="eventFooter">
                                <div class="eventPrice">
                                    <h3 class="tittle">{{ amount_with_currency_symbol($data->cost) }}</h3>
                                </div>
                                <div class="btn-wrapper mb-20">
                                    <a href="{{route('tenant.frontend.event.payment',$data->slug)}}" class="cmn-btn-outline04"> {{__('Book Ticket')}} </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

