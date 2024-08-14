
<div class="mapWrapper mb-50">
    <div class="small-tittle mb-40">
        <h3 class="tittle lineStyleOne">{{get_static_option('event_map_area_'.get_user_lang().'_title',__('Event Location'))}}</h3>
    </div>

    @php
       $map =  sprintf(
            '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe>',
            rawurlencode($event->venue_location),10,$event->venue_location );
    @endphp

      {!! $map !!}

    <div class="row">
        <div class="col-xl-6 mb-10">
            <p>{{ $event->venue_location }}</p>
        </div>
        <div class="col-xl-6 mb-10">
            <p>{{__('Date:')}} {{$event->date}}</p>
            <p>{{__('Time:')}} {{ $event->time }}</p>
        </div>
    </div>
</div>
