@extends(route_prefix().'admin.admin-master')

    @section('title') {{__('Edit User')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>

                <x-slot name="left">
                     <h4 class="card-title mb-5">{{__('Edit User')}}</h4>
                </x-slot>

                <x-slot name="right">
                    <a href="{{route('tenant.admin.user')}}" class="btn btn-info btn-sm">{{__('All Users')}}</a>
                </x-slot>

                </x-admin.header-wrapper>
                    <x-error-msg/>
                    <x-flash-msg/>

                <form action="{{route('tenant.admin.user.update.profile')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <x-fields.input type="text" name="name" value="{{$user->name}}" label="{{__('Name')}}"/>
                    <x-fields.input type="text" name="username" value="{{$user->username}}" label="{{__('Username')}}"/>
                    <x-fields.input type="email" name="email" value="{{$user->email}}" label="{{__('Email')}}"/>
                    <x-fields.input type="text" name="mobile" value="{{$user->mobile}}" label="{{__('Mobile')}}"/>

                    <x-fields.country-select name="country" value="{{$user->country}}" label="{{__('Country')}}"/>
                    <x-fields.input type="text" name="city" value="{{$user->city}}" label="{{__('City')}}"/>
                    <x-fields.input type="text" name="state" value="{{$user->state}}" label="{{__('State')}}"/>
                    <x-fields.input type="text" name="company" value="{{$user->company}}" label="{{__('Company')}}"/>
                    <x-fields.input type="text" name="address" value="{{$user->address}}"  label="{{__('Address')}}"/>

                    <x-fields.media-upload name="image" title="{{__('Image')}}" id="{{$user->image}}" value="{{$user->image}}" dimentions="{{__('120 X 120 px image recommended')}}"/>


                    <button type="submit" class="btn btn-gradient-primary me-2 mt-5">{{__('Update')}}</button>

                </form>


            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>
@endsection

