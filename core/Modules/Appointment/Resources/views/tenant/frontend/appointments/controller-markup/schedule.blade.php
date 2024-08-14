
<ul class="tabs barberShop_scheduleTabs__list">
    @foreach($typeWiseTime as $type_id => $values)
        @php
            $main_loop = $loop;
        @endphp
        @foreach($values->unique("day_type") as $value)
            <li data-tab="day-id-{{ $type_id }}" class="{{ $main_loop->first ? "active" : ""}}">{{ $value->type?->title }}</li>
        @endforeach
    @endforeach
</ul>


<div class="barberShop_scheduleTabs__contents mt-4">
    @foreach($typeWiseTime as $type_id => $values)
        @php
            $main_loop = $loop;
        @endphp
        <div class="tab_content_item {{ $main_loop->first ? "active" : ""}}" id="day-id-{{ $type_id }}">
            @foreach($values as $value)
                <div class="barberShop_scheduleTabs__time d-inline" data-time="{{$value->time}}">
                    <span class="barberShop_scheduleTabs__time__item">{{ $value->time }}</span>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
