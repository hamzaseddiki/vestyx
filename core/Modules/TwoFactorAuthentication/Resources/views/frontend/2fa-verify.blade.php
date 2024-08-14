@extends('landlord.frontend.frontend-page-master')
@section('title')
    {{__('2FA Verify')}}
@endsection

@section('page-title')
    {{__('2FA Verify')}}
@endsection

@section('content')

<div class="signup-area padding-top-70 padding-bottom-100">
    <div class="container">
        <div class="signup-wrapper">
            <div class="signup-contents">
                <h3 class="signup-title"> {{ __('Enter 2FA Security Code')}} </h3>
                <x-error-msg/>
                <x-flash-msg/>
               <div class="alert alert-info my-4" role="alert">
                    {{__('please open your google authenticator app and enter the given security code')}}
                    <button type="button" class="close bg-danger text-white" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="signup-forms" action="{{ route('frontend.verify.2fa.code')}}" method="post">
                    @csrf
                    <div class="col-lg-12 col-md-12">
                        <div class="input-form input-form2">
                            <label class="signup-label"> {{__('Enter code*')}} </label>
                            <input type="password" name="one_time_password" placeholder="{{__('Enter Code')}}">
                        </div>
                    </div>

                     <div class="btn-wrapper margin-top-20">
                       <button type="submit" class="cmn-btn btn-bg-1" >{{ __('Verify Account') }} </button>
                    </div>

                </form>
            </div>
            <br>

        </div>
    </div>
</div>
@endsection
