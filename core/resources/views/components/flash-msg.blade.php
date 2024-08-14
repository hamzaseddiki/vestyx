
@if(session()->has('msg'))
    <div class="main_success_message alert alert-{{session('type')}}">
        {!! Purifier::clean(session('msg')) !!}
    </div>
@endif
