@extends('landlord.frontend.user.dashboard.user-master')

@section('title')
    {{__('Two Factor Authentication Settings')}}
@endsection

@section('page-title')
    {{__('Two Factor Authentication Settings')}}
@endsection

@section('section')
    <x-error-msg/>
    <x-flash-msg/>

    <div class="body-overlay"></div>
    <div class="dashboard-area dashboard-padding">
        <div class="container-fluid">
            <div class="dashboard-contents-wrapper">
                <div class="dashboard-icon">
                    <div class="sidebar-icon">
                        <i class="las la-bars"></i>
                    </div>
                </div>


                <div class="dashboard-right">
                    <div class="dashboard-settings margin-top-40">
                        <h2 class="dashboards-title">  {{__('Two Factor Authentication Settings')}} </h2>
                    </div>

                    <p class="my-3">{{__('Two factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity.')}}</p>

                    @if($user->loginSecurity == null)

                        <form class="form-horizontal" method="POST" action="{{ route('landlord.user.enable2fa') }}">
                           @csrf
                            <input type="hidden" name="form_type" value="generate_secret_key">

                            <div class="btn-wrapper margin-top-50">
                            <button type="submit" class="cmn-btn btn-bg-1" > {{__('Generate Secret Key to Enable 2FA')}} </button>
                            </div>
                        </form>


                    @elseif(!optional($user->loginSecurity)->google2fa_enable)

                        {{ __('1. Scan this QR code with your Google Authenticator App.') }}<br>
                        <!-- QR code img  -->

                        {!! $google2fa_url !!}
                        
                        <br><br>
                        {{ __('2. Enter the pin from Google Authenticator app:') }}<br><br>
                        <form class="form-horizontal" method="POST" action="{{ route('landlord.user.enable2fa') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="form_type" value="enable_2fa">
                            <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                               <div class="form-group">
                                   <label for="secret" class="control-label">{{ __('Authenticator Code') }}</label>
                                   <input id="secret" type="number"  class="form-control col-md-4" name="secret" placeholder="input code:" required>
                               </div>
                                @if ($errors->has('verify-code'))
                                    <span class="help-block"><strong>{{ $errors->first('verify-code') }}</strong></span>
                                @endif
                            </div>
                            <br>

                             <div class="btn-wrapper">
                            <button type="submit" class="cmn-btn btn-bg-1" > {{ __('Enable 2FA') }} </button>
                            </div>

                        </form>

                    @elseif($user->loginSecurity?->google2fa_enable)
                        <div class="alert alert-success">
                            {{ __('2FA is currently') }} <strong>{{ __('enabled') }}</strong> {{ __('on your account.') }}
                        </div>

                        <p>{{ __('If you are looking to disable TwoFactor Authentication. Please confirm your password and Click Disable 2FA Button.') }}</p>

                        <form class="form-horizontal" method="POST" action="{{ route('disable2fa') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                <label for="change-password" class="control-label"><strong>{{ __('Current Password') }}</strong></label>
                                <input id="current-password" type="password" class="form-control col-md-4" name="current-password" required>
                                @if ($errors->has('current-password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                        </span>
                                @endif
                            </div>

                              <div class="btn-wrapper">
                                <button type="submit" class="cmn-btn btn-bg-1" >{{ __('Disable 2FA') }} </button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

 @endsection
