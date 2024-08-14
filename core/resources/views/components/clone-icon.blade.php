<form action="{{$action}}" method="post" class="d-inline-block">
    @csrf
    <input type="hidden" name="item_id" value="{{$id}}">
    <button type="submit" class="btn btn-xs btn-secondary btn-sm mb-3 mr-1"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Clone')}}"
    >
        <i class="las la-copy"></i>
    </button>
</form>
