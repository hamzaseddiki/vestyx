@extends('layouts.app')

@section('title')
    {{__('Email Verification')}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">{{ __('Verify Your Email Address') }}</h4>
                </div>

                <div class="card-body">
                    <x-flash-msg/>
                    <x-error-msg/>

               <h5> {{ __('Before proceeding, please check your email for a verification link.') }}</h5>

                        <form action="{{route('tenant.admin.email.verify')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                            @csrf
                            <div class="form-group mt-2">
                                <input type="text" name="verify_code" class="form-control" placeholder="{{__('Verify Code')}}">
                            </div>
                            <div class="form-group btn-wrapper my-3">
                                <button type="submit" id="verify" class="btn btn-primary">{{__('Verify Email')}}</button>
                            </div>
                            <div class="row mb-4 rmber-area">
                                <div class="col-12 text-center">
                                    <a href="{{route('tenant.admin.email.verify.resend')}}">{{__('Send Verify Code Again?')}}</a>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
