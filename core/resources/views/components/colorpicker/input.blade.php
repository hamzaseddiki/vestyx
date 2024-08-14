<div class="form-group">
    <label>{{$label}}</label>
    <div class="spectrum_color_picker" title="{{__('Click to change color')}}" style="background-color: {{$value ?? ''}}"></div>
    <input type="hidden" name="{{$name}}" class="form-control"  value="{{$value ?? ''}}">
    @if(isset($info))
        <small class="info-text d-block mt-2">{!!  $info !!}</small>
    @endif
</div>
