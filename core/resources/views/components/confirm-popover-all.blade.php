
<a tabindex="0" href="{{$url}}" class="btn btn-danger btn-sm mr-1 swal_change_confirm_button_all">
    {{$title ??  __('Clear All')}}
</a>

<form method='get' action='{{$url ?? ''}}' class="d-none">
    <br>
    <button type="submit" class="swal_form_submit_btn"></button>
</form>
