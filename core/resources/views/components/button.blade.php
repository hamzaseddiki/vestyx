<button type="{{$type ?? 'button'}}"  class="btn-xs mb-3 mr-1 btn btn-{{$class ?? 'primary'}}"
   @if(isset($popover)) data-bs-toggle="tooltip" data-bs-placement="top" title="{{$popover}}" @endif
>{{$slot}}</button>
