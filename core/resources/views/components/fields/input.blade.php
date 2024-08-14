@php $type = $type ?? 'text';@endphp
<div class="form-group {{$extra ?? ''}}">
    @if(isset($type) && $type !== 'hidden')
        <label>{{$label}}</label>
    @endif
    <input type="{{$type ?? 'text'}}"
           name="{{$name}}"
           class="form-control {{$class ?? ''}}"
           @if( isset($type) && $type !== 'hidden')
           placeholder="{{$placeholder ?? $label}}"
           @endif
           value="{{$value ?? ''}}" min="{{$min ?? ''}}" step="0.01">
    @if(isset($info))
        <small class="info-text text-primary d-block mt-2">{!!  $info !!}</small>
    @endif
</div>
