@php
    $all_google_fonts = \Illuminate\Support\Facades\Cache::get('typography_family');
    if(is_null($all_google_fonts)){
          $all_google_fonts = file_get_contents(public_path('google-fonts.json'));
          \Illuminate\Support\Facades\Cache::put('typography_family',json_decode($all_google_fonts,true),6400);
    }
    $all_google_fonts = \Illuminate\Support\Facades\Cache::get('typography_family');
    $font_families = array_keys($all_google_fonts);
@endphp
<div class="form-group">
    <label for="status">{{$title}}</label>
    <select name="{{$name}}" class="form-control select2">
        <option value="">{{__('select family')}}</option>
        @foreach($font_families as $family)
            <option value="{{$family}}">{{$family}}</option>
        @endforeach
    </select>
    @if(isset($info))
        <small class="info-text d-block mt-2">{!!  $info !!}</small>
    @endif
</div>
