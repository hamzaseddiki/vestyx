@php
$random_number = [];
@endphp
<ul class="nav nav-tabs" role="tablist">
    @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
        @php
        $rnd_str = \Illuminate\Support\Str::random(16);
         $random_number[] = $rnd_str;
        @endphp
        <li class="nav-item" role="presentation">
            <button class="nav-link @if($loop->first) active @endif" data-bs-toggle="tab" data-bs-target="#home-{{$rnd_str}}-{{$lang->slug}}" type="button" role="tab"  aria-selected="@if($loop->first) true @else false @endif">
                {{$lang->name}}
            </button>
        </li>
    @endforeach
</ul>
<div class="tab-content mt-3 mb-5">
    @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
        @php $$slot = $lang->slug @endphp
        <div class="tab-pane fade @if($loop->first) show active @endif" data-langslug="{{$lang->slug}}" id="home-{{$random_number[$loop->index]}}-{{$lang->slug}}" role="tabpanel" >
            {{$$$slot}}
        </div>
    @endforeach
</div>
