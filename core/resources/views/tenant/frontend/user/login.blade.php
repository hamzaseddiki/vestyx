@extends('tenant.frontend.frontend-page-master')

@section('title')
    {{__('User Login')}}
@endsection

@section('page-title')
    {{__('User Login')}}
@endsection

@section('style')
    <style>
         .form-Wrapper {
            background: #FFFFFF;
            -webkit-box-shadow: 0px 2px 11px 3px rgba(26, 40, 68, 0.06);
            box-shadow: 0px 2px 11px 3px rgba(26, 40, 68, 0.06) !important;
            border-radius: 12px;
            padding: 50px 60px;
            border-radius: 8px;
            border: 1px solid #E5E5E5;
        }
    </style>
@endsection

@section('content')

<section class="loginArea section-padding2">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-xxl-6 col-xl-7 col-lg-9">
                <form action="#" class="form-Wrapper" id="login_form_order_page">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="section-tittle section-tittle2 text-center mb-30">
                                <h2 class="tittle p-0">{{__('Login in your ')}}<span class="color"> {{__('Account')}}</span></h2>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                        <div class="error-wrap"></div>
                            <div class="input-form input-form2">
                                <input type="text" name="username" placeholder="Enter your username">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="input-form input-form2">
                                <input type="password" name="password" placeholder="Password">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="passRemember">
                                <label class="checkWrap2">{{__('Remember me')}}
                                    <input class="effectBorder" type="checkbox" value="">
                                    <span class="checkmark"></span>
                                </label>
                                <!-- forgetPassword -->
                                <div class="forgetPassword mb-25">
                                    <a href="{{route('tenant.user.forget.password')}}" class="forgetPass">{{__('Forget passwords?')}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="btn-wrapper text-center mt-20">
                                <button type="button" id="login_btn" class="cmn-btn1 w-100 mb-40">{{__('Login')}}</button>

                                <p class="sinUp mb-20"><span>{{__('Don’t have an account ?')}} </span>
                                    <a href="{{route('tenant.user.register')}}" class="singApp">{{__('Sign Up')}}</a>
                                </p>

                                    <x-social-login-markup/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')
   <x-custom-js.ajax-login/>
@endsection
