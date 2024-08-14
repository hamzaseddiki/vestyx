@extends('landlord.frontend.user.dashboard.user-master')
@section('title')
    {{__('Email Verify')}}
@endsection
@section('page-title')
    {{__('Email Verify')}}
@endsection
@section('content')

    <section class="login-page-area padding-top-120 padding-bottom-120">
        <div class="container-max">
            <div class="row justify-content-center">
               <div class="col-lg-6">
                   <div class="contact-form-wrapper">
                       <h2 class="title">{{__("Verify your email")}}</h2>
                       <x-flash-msg/>
                       <x-error-msg/>
                       <form action="{{route('landlord.user.email.verify')}}" method="post">
                           @csrf
                           <div class="form-group">
                               <label>{{__('Verify Code')}}</label>
                               <input type="text" name="verify_code" placeholder="{{__('type verify code')}}">
                           </div>
                           <div class="btn-wrapper">
                               <button type="submit" id="login_button" class="btn-default">{{__('Confirm')}}</button>
                           </div>
                           <div class="extra-wrap margin-top-20">
                               <span>{{__('Do not get code?')}} <a href="{{route('landlord.user.email.verify.resend')}}">{{__('resent code')}}</a></span>
                           </div>
                       </form>
                   </div>
               </div>
            </div>
        </div>
    </section>
@endsection

