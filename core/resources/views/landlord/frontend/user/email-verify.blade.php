@extends('landlord.frontend.frontend-page-master')
@section('title')
    {{__('Verify Your Account')}}
@endsection
@section('page-title')
    {{__('Verify Your Account')}}
@endsection
@section('content')


    <div class="loginArea bottom-padding section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-7 col-lg-9 login-Wrapper">
                <h2 class="title text-center mb-4">{{__("Verify your email")}}</h2>
                    <div class="row">
                        <x-error-msg/>
                        <x-flash-msg/>
                        <form action="{{route('landlord.user.email.verify')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                            @csrf

                            <div class="col-lg-12 col-md-12">
                                <div class="input-form input-form2">
                                    <input type="text" name="verify_code" placeholder="{{__('Verify Code')}}">
                                </div>
                            </div>

                            <div class="form-group btn-wrapper my-3">
                                <button type="submit" class="cmn-btn1 w-100 mb-40">{{__('Verify Email')}}</button>
                            </div>

                            <div class="row mb-4 rmber-area">
                                <div class="col-12 text-center">
                                    <a href="{{route('landlord.user.email.verify.resend')}}" id="send">{{__('Send Verify Code Again?')}}</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



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
