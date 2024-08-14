@extends('tenant.frontend.user.dashboard.user-master')
@section('title')
    {{__('Verify Your Account')}}
@endsection
@section('page-title')
    {{__('Verify Your Account')}}
@endsection
@section('content')
        <section class="loginArea section-padding2">
            <div class="container">
                <div class="row justify-content-center">

                    <div class="col-xxl-6 col-xl-7 col-lg-9">
                        <h4 class="title text-center mb-3">{{ __('Check Mail for Verification code') }}</h4>
                        <x-flash-msg/>
                        <x-error-msg/>

                        <form action="{{route('tenant.user.email.verify')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                            @csrf

                            <div class="input-form input-form2">
                                <input type="text" name="verify_code" placeholder="{{__('Verify Code')}}">
                            </div>

                            <div class="form-group btn-wrapper mb-3">
                                <button type="submit" id="login_btn" class="cmn-btn1 w-100">{{__('Submit')}}</button>
                            </div>

                            <div class="row rmber-area">
                                <div class="col-12 text-center">
                                    <a href="{{route('tenant.user.email.verify.resend')}}" id="send">{{__('Send Verify Code Again?')}}</a>
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
            <x-btn.custom :id="'verify'" :title="__('Verifying')"/>
            <x-btn.custom :id="'send'" :title="__('Sending Verify Code')"/>
        });
        })(jQuery);
    </script>
@endsection
