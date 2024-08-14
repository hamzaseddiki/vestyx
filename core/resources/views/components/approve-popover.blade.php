@php
    if (isset($permissions) && !auth('admin')->user()->can($permissions)){
        return;
    }
@endphp

<a tabindex="0" class="btn btn-success btn-xs mb-3 mr-1 swal_change_approve_payment_button"
   data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Approve or Complete')}}"
>
    <i class="mdi mdi-check"></i>
</a>
<form method='post' action='{{$url}}' class="d-none">
    <input type='hidden' name='_token' value='{{csrf_token()}}'>
    <br>
    <button type="submit" class="swal_form_submit_btn d-none"></button>
</form>
