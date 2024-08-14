@php
    if (isset($permissions) && !auth('admin')->user()->can($permissions)){
        return;
    }
@endphp

<a href="{{$url}}" class="btn-sm mb-3 mr-1 btn btn-{{$class ?? 'primary'}} {{$extraclass ?? ''}}" @if(isset($target)) target="{{$target}}" @endif
   @if(isset($popover)) data-bs-toggle="tooltip" data-bs-placement="top" title="{{$popover ?? ''}}" @endif
>{{$slot}}</a>
