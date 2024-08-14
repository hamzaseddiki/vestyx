@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('Forget Password')}}
@endsection

@section('page-title')
    {{__('Forget Password')}}
@endsection

@section('custom-page-title')
    {{__('Forget Password')}}
@endsection

@section('content')

<section class="loginArea section-padding2">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-xxl-6 col-xl-7 col-lg-9">
                <form action="{{route('tenant.user.forget.password')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                    @csrf
                    <x-error-msg/>
                    <x-flash-msg/>
                    <div class="input-form input-form2">
                        <input type="text" name="username" placeholder="{{__('Enter your username')}}">
                    </div>
                    <div class="btn-wrapper text-center">
                          <button type="submit" id="login_btn" class="cmn-btn1 w-100 mb-40">{{__('Send Reset Mail')}}</button>
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
            <x-btn.custom :id="'send'" :title="__('Sending')"/>
        });
        })(jQuery);
    </script>
@endsection
