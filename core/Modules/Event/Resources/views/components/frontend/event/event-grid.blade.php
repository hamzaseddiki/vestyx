@php
    $user_lang = get_user_lang();
@endphp

@forelse($allEvent as $item)
    <div class="col-lg-{{$col ?? 4}} col-md-6">
        <div class="singleEvents mb-24">
            <div class="event-img imgEffect">
                <a href="{{route('tenant.frontend.event.single',$item->slug)}}">
                    {!! render_image_markup_by_attachment_id($item->image) !!}
                </a>
                <div class="img-text">
                    <span class="content">{{ date('d',strtotime($item->date)) }} <span class="month">{{ date('M',strtotime($item->date)) }}</span></span>
                </div>
            </div>
            <div class="eventCaption">
                <h3>
                    <a href="{{route('tenant.frontend.event.single',$item->slug)}}" class="tittle">{{ $item->getTranslation('title',$user_lang) }}</a>
                </h3>
                <p class="pera">{{ \Illuminate\Support\Str::words(purify_html($item->getTranslation('content',$user_lang)),40) }} </p>
                <ul class="cartTop">
                    <li class="listItmes"><i class="fa-solid fa-location-dot icon"></i> {{$item->venue_location}}</li>
                    <li class="listItmes"><i class="fa-regular fa-clock icon"></i>{{$item->time}}</li>
                    <li class="listItmes"><i class="fa-solid fa-person-dress icon"></i> {{ !empty($item->available_ticket) ?  ($item->total_ticket - $item->available_ticket) : 0 }} {{__('participate')}}</li>
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
    @empty
        <div class="col-lg-12">
            <div class="alert alert-warning event_filter_top_message">
                <h4 class="text-center">{{__('No Event Available In') .' : ' .$searchTerm ?? ''}}</h4>
            </div>
        </div>
    @endforelse

