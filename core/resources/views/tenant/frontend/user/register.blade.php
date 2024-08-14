@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('User Register')}}
@endsection

@section('page-title')
    {{__('User Register')}}
@endsection
@section('content')

<section class="registerArea section-padding">
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xxl-6 col-xl-7 col-lg-9">

                <form action="{{route('tenant.user.register.store')}}" class="form-Wrapper" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="section-tittle section-tittle2 text-center mb-30">
                                <h2 class="tittle p-0">{{__('Register new')}} <span class="color"> {{__('Account')}}</span></h2>
                            </div>
                        </div>

                        <x-error-msg/>
                        <x-flash-msg/>
                        <div class="col-lg-12 col-md-12">
                            <div class="input-form input-form2">
                                <input type="text" placeholder="Name" name="name" value="{{old('name')}}">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="input-form input-form2">
                                <input type="text" placeholder="Username" name="username" value="{{old('username')}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-form input-form2">
                                <input type="email" placeholder="Email" name="email" value="{{old('email')}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-form input-form2">
                                <input type="text" placeholder="Address" name="address" value="{{old('address')}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-form input-form2">
                                <input type="password" placeholder="Create password" name="password">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-form input-form2">
                                <input type="password" placeholder="Confirm Password" name="password_confirmation">
                            </div>
                        </div>

                        @php
                            $terms_page_route = get_dynamic_page_name_by_id(get_static_option('terms_condition_page')) ?? '#';
                            $policy_page_route =  get_dynamic_page_name_by_id(get_static_option('privacy_policy_page')) ?? '#';
                        @endphp

                        <div class="col-md-12">
                            <label class="checkWrap2">{{__('I’v read and agree with')}}
                                <a href="{{route('tenant.dynamic.page',$terms_page_route)}}">{{__('Terms and condition')}}</a> {{__('and')}}
                                <a href="{{route('tenant.dynamic.page',$policy_page_route)}}">{{__('privacy policy')}}</a>
                                <input class="effectBorder" type="checkbox" value="">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="col-sm-12">
                            <div class="btn-wrapper text-center mt-20">

                                <button type="submit" class="cmn-btn1 w-100 mb-40">{{__('Register')}}</button>

                                <p class="sinUp mb-20"><span>{{__('Already have an account ?')}} </span>
                                    <a href="{{route('tenant.user.login')}}" class="singApp">{{__('Login')}}</a>
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
    <script>
        (function($){
        "use strict";
        $(document).ready(function () {
            <x-btn.custom :id="'register'" :title="__('Please Wait..')"/>
        });
        })(jQuery);
    </script>
@endsection
