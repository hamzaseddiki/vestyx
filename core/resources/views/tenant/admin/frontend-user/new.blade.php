@extends(route_prefix().'admin.admin-master')

    @section('title') {{__('Add New User')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                <x-slot name="left">
                <h4 class="card-title mb-5">{{__('Add New User')}}</h4>
                </x-slot>

                <x-slot name="right">
                    <a href="{{route('tenant.admin.user')}}" class="btn btn-info btn-sm">{{__('All Users')}}</a>
                </x-slot>

                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample"  action="{{route('tenant.admin.user.new')}}" method="post">
                    @csrf
                    <x-fields.input type="text" name="name" class="form-control" placeholder="{{__('name')}}" label="{{__('Name')}}"/>
                    <x-fields.input type="text" name="username" class="form-control" placeholder="{{__('username')}}" label="{{__('Username')}}"/>
                    <x-fields.input type="email" name="email" class="form-control" placeholder="{{__('email')}}" label="{{__('Email')}}"/>
                    <x-fields.input type="text" name="mobile" class="form-control" placeholder="{{__('mobile')}}" label="{{__('Mobile')}}"/>


                    <x-fields.country-select name="country" label="{{__('Country')}}" class="mb-3"/>
                    <x-fields.input type="text" name="city" class="form-control" placeholder="{{__('city')}}" label="{{__('City')}}"/>
                    <x-fields.input type="text" name="state" class="form-control" placeholder="{{__('state')}}" label="{{__('State')}}"/>
                    <x-fields.input type="text" name="company" class="form-control" placeholder="{{__('company')}}" label="{{__('Company')}}"/>
                    <x-fields.input type="text" name="address" class="form-control" placeholder="{{__('address')}}" label="{{__('Address')}}"/>

                    <x-fields.media-upload name="image" title="{{__('Image')}}" dimentions="{{__('120 X 120 px image recommended')}}"/>
                    <x-fields.input type="password" name="password" class="form-control"  label="{{__('Password')}}"/>
                    <x-fields.input type="password" name="password_confirmation" class="form-control"  label="{{__('Confirm Password')}}"/>

                    <button type="submit" class="btn btn-gradient-primary me-2 mt-5">{{__('Submit')}}</button>

                </form>


            </div>
        </div>
    </div>

    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>
@endsection

