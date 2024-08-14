<div class="form-group">
    <label>{{$label}}</label>
    @if(isset($link))
        <small>
            <a href="{{$link ?? ''}}" target="_blank">{{__('View')}}</a>
        </small>
    @endif
    <label class="switch {{$class ?? ''}}">
        <input type="checkbox" class="{{ $extra ?? '' }}" @if(!empty($dataValue)) value="{{$dataValue ?? ''}}" @endif name="{{$name}}" @if(!empty($value)) checked @endif>
        <span class="slider onff"></span>
    </label>
    @if(isset($info))
        <small class="info-text d-block mt-2">{{$info}}</small>
    @endif


</div>
