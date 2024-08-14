

<div class="form-group">
    <label for="status">{{$title}}</label>
    <select name="{{$name}}" class="form-control {{$class ?? ''}}">
       {{$slot}}
    </select>
    @if(isset($info))
        <small class="info-text d-block mt-2">{!!  $info !!}</small>
    @endif
</div>
