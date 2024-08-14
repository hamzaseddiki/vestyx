<div class="form-group">
    <label>{{$label}}</label>
    <textarea  name="{{$name}}" class="summernote {{$class ?? ''}}"  rows="4" placeholder="{{$label}}">{{$value ?? ''}}</textarea>
    @if(isset($info))
        <small class="info-text d-block mt-2">{!! $info !!}</small>
    @endif
</div>
