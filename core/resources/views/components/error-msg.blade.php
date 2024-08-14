
@if($errors->any())
    <div class="alert alert-danger main_error_message">
{{--        <button type="button" class="close" data-dismiss="alert">×</button>--}}
        <ul class="list-none">
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif






