@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Application Settings')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Application Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.general.application.settings')}}">
                    @csrf
                    <div class="form-group">
                        @php
                            $list = DateTimeZone::listIdentifiers();
                        @endphp
                        <label for="timezone">{{__('Select Timezone')}}</label>
                        <select class="form-control" name="timezone" id="timezone">
                            @foreach($list as $zone)
                                <option value="{{$zone}}" {{$zone == get_static_option('timezone') ? 'selected' : ''}}>{{$zone}}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted"></small>
                    </div>

                    @if(!tenant())
                        <x-fields.input type="text" value="{{get_static_option('set_app_name_env')}}" name="set_app_name_env" label="{{__('App Name')}}"/>
                    @endif
                    <x-fields.switcher value="{{get_static_option('set_environment_env')}}" name="set_environment_env" label="{{__('Enable/Disable Live Server or Localhost')}}" info="{{__('Yes means site is in live server and no means localhost..!')}}"/>
                    <x-fields.switcher value="{{get_static_option('set_app_debug_env')}}" name="set_app_debug_env" label="{{__('True/False App Debug')}}" info="{{__('True means App Debug True and False means App Debug false')}}"/>
                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-media-upload.js/>
@endsection
