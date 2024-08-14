
@extends(route_prefix().'.admin.admin-master')
@section('title') {{__('Change Password')}} @endsection
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Change Password')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.change.password')}}">
                    @csrf
                    <div class="form-group">
                        <label>{{__('Password')}}</label>
                        <input type="password" name="password" class="form-control"  placeholder="{{__('Password')}}">
                    </div>
                    <div class="form-group">
                        <label>{{__('Confirm Password')}}</label>
                        <input type="password" name="password_confirmation" class="form-control"  placeholder="{{__('Password')}}">
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
