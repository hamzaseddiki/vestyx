@extends(route_prefix().'.admin.admin-master')
@section('title') {{__('Edit Profile')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Edit Profile')}}</h4>

                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample" method="post"
                      action="{{route(route_prefix().'admin.edit.profile')}}"
                >
                    @csrf
                    <x-fields.input :label="__('Name')" :name="'name'"  :value="auth('admin')->user()->name"/>
                    <x-fields.input :label="__('Email')" :name="'email'" :type="'email'" :value="auth('admin')->user()->email" />
                    <x-fields.input :label="__('Mobile')" :name="'mobile'"  :value="auth('admin')->user()->mobile"/>
                    <x-fields.media-upload name="image" title="{{__('Image')}}" :id="auth('admin')->user()->image"/>

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

