@php
    if (isset($permissions) && !auth('admin')->user()->can($permissions)){
        return;
    }
@endphp

<a tabindex="0" class="btn btn-danger btn-xs mb-3 mr-1 swal_delete_button"
   data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}"
>
    <i class="mdi mdi-delete"></i>
</a>
<form method='post' action='{{$url}}' class="d-none">
    <input type='hidden' name='_token' value='{{csrf_token()}}'>
    <br>
    <button type="submit" class="swal_form_submit_btn d-none"></button>
</form>
