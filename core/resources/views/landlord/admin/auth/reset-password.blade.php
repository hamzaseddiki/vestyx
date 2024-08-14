@extends('layouts.app')
@section('title')
    {{__('Reset Password')}}
@endsection

@section('content')

    <div class="row flex-grow">
        <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
                <div class="brand-logo text-center">
                    {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                </div>
                <h2 class="text-center my-4">{{__('Forget Password ?')}}</h2>
                <x-error-msg/>
                <x-flash-msg/>
                <form action="{{route(route_prefix().'reset.password.change')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                    @csrf
                    <input type="hidden" name="token" value="{{$token}}">
                    <div class="form-group">
                        <input type="text" id="username" class="form-control" readonly value="{{$username}}" name="username">
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" class="form-control" name="password" placeholder="Enter New Password">
                    </div>
                    <div class="form-group">
                        <input type="password" id="password_confirmation"  class="form-control" name="password_confirmation" placeholder="Confirm Password">
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" id="login_submit_btn">{{__('Reset Password')}}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

