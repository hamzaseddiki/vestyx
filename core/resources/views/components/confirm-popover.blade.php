
<a tabindex="0" class="btn btn-danger btn-sm mr-1 swal_change_confirm_button"
   data-bs-toggle="tooltip" data-bs-placement="top" title="{{$title ?? __('Approve')}}"
>
   {{$title ??  __('Approve')}}
</a>
<form method='post' action='{{$url ?? ''}}' class="d-none">
    <input type='hidden' name='_token' value='{{csrf_token()}}'>
    <br>
    <button type="submit" class="swal_form_submit_btn"></button>
</form>
