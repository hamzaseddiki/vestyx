<form action="{{$route}}" method="post" class="d-inline-block">
    @csrf
    <input type="hidden" name="item_id" value="{{$id}}">
    <button type="submit" title="{{__('clone this to new draft')}}"
            class="btn btn-xs btn-secondary btn-sm mb-3 mr-1">
        <i class="far fa-copy"></i>
    </button>
</form>
