@extends('landlord.frontend.user.dashboard.user-master')

@section('style')
    <style>
        .edit_profile .nice-select .list{
            height: 300px;
            overflow: scroll;
        }
    </style>
@endsection

@section('title')
    {{__('Edit Profile')}}
@endsection

@section('page-title')
    {{__('Edit Profile')}}
@endsection

@section('section')

    <div class="parent edit_profile">
        <h2 class="title">{{__('Edit Profile')}}</h2>
        <form action="{{route('landlord.user.profile.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="name">{{__('Name')}}</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{$user_details->name}}">
                </div>
                <div class="form-group col-lg-6">
                    <label for="email">{{__('Email')}}</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{$user_details->email}}">
                </div>
                <div class="form-group col-lg-6">
                    <label for="phone">{{__('Phone')}}</label>
                    <input type="tel" class="form-control" name="mobile" value="{{$user_details->mobile}}">
                </div>
                <div class="form-group col-lg-6">
                    <label for="address">{{__('Company')}}</label>
                    <input type="text" class="form-control" id="address" name="company" value="{{$user_details->company}}">
                </div>
                <div class="form-group col-lg-6 ">
                    <label for="address">{{__('Country')}}</label>
                    <select name="country" class="form-control niceSelect_active">
                        <option disabled="" selected>{{__('Select a country')}}</option>
                        @foreach($countries as $country)
                            <option value="{{$country->id}}" @selected(old('country',$country->id ) == $user_details->country)>{{$country->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-lg-6">
                    <label for="address">{{__('Address')}}</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{$user_details->address}}">
                </div>
                <div class="form-group col-lg-6">
                    <label for="state">{{__('State')}}</label>
                    <input type="text" class="form-control" id="state" name="state" value="{{$user_details->state}}">
                </div>

                <div class="form-group col-lg-6">
                    <label for="state">{{__('City')}}</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{$user_details->city}}">
                </div>


            <div class="btn-wrapper mt-2">
                  <button type="submit" class="btn-default boxed-btn">{{__('Save changes')}}</button>
            </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')

    <script>

        (function($){
            "use strict"
        $(document).ready(function(){
            var selectdCountry = "{{$user_details->country}}";
            $('#country option[value="'+selectdCountry+'"]').attr('selected',true);
        });

        }(jQuery));
    </script>

    <script>
        $('.close-bars, .body-overlay').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $('.sidebar-icon').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });
    </script>

@endsection

