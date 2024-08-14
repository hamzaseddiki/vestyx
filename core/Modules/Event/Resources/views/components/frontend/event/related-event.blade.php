<div class="relatedEvent bottom-padding">
    <div class="container">
        <!-- Section Tittle -->
        <div class="row">
            <div class="col-xxl-7 col-xl-7 col-lg-8 col-md-9 col-sm-10">
                <div class="section-tittle mb-50">
                    <h2 class="tittle wow ladeInUp" data-wow-delay="0.0s">
                        {{__('Related')}}
                    </h2>
                </div>
            </div>
        </div>
        <!-- Single Events -->
        <div class="row">
            @foreach($relatedEvents as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="singleEvents mb-24">
                        <div class="event-img imgEffect">
                            <a href="{{ route('tenant.frontend.event.single',$item->slug) }}">
                                {!! render_image_markup_by_attachment_id($item->image) !!}
                            </a>
                            <div class="img-text">
                                <span class="content">{{ date('d',strtotime($item->date)) }} <span class="month">{{ date('M',strtotime($item->date)) }}</span></span>
                            </div>
                        </div>
                        <div class="eventCaption">
                            <h3><a href="{{ route('tenant.frontend.event.single',$item->slug) }}" class="tittle">{{ $item->getTranslation('title',get_user_lang()) }}</a></h3>
                            <p class="pera">{!! \Illuminate\Support\Str::words(purify_html($item->content),15) !!}</p>
                            <ul class="cartTop">
                                <li class="listItmes"><i class="fa-solid fa-location-dot icon"></i> {{$item->venue_location}}</li>
                                <li class="listItmes"><i class="fa-regular fa-clock icon"></i>{{$item->time}}</li>
                                <li class="listItmes"><i class="fa-solid fa-person-dress icon"></i> {{!empty($item->available_ticket) ?  ($item->total_ticket - $item->available_ticket) : 0 }} {{__('participate')}}</li>
                            </ul>
                            <!-- Blog Footer -->
                            <div class="eventFooter">
                                <div class="eventPrice">
                                    <h3 class="tittle">{{ amount_with_currency_symbol($item->cost) }}</h3>
                                </div>
                                <div class="btn-wrapper mb-20">
                                    <a href="{{route('tenant.frontend.event.payment',$item->slug)}}" class="cmn-btn-outline04"> {{__('Book Ticket')}} </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
