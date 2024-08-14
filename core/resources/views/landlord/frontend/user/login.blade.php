@extends('landlord.frontend.frontend-page-master')

@section('title')
    {{__('User Login')}}
@endsection

@section('page-title')
    {{__('User Login')}}
@endsection

@section('content')

    @php

        $username = "";
        $password = "";
        if(preg_match('/(multipurposesass)/',url('/'))){
            $username = "test";
            $password = "12345678";
        }

        $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
        $title = get_static_option('landlord_user_login_'.$current_lang.'_title');

        if (str_contains($title, '{h}') && str_contains($title, '{/h}'))
        {
            $text = explode('{h}',$title);
            $highlighted_word = explode('{/h}', $text[1])[0];
            $highlighted_text = '<span class="color">'. $highlighted_word .'</span>';
            $final_title = '<h2 class="tittle wow fadeInUp" data-wow-delay="0.0s">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $title).'</h2>';
        } else {
            $final_title = '<h2 class="tittle wow fadeInUp" data-wow-delay="0.0s">'. $title .'</h2>';
        }
    @endphp

    <div class="loginArea section-padding2 loginArea login-Wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-7 col-lg-9 login-Wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-tittle section-tittle2 mb-ss30">
                                {!! $final_title !!}
                            </div>
                        </div>

                <x-error-msg/>
                <x-flash-msg/>

                <form action="" method="post" enctype="multipart/form-data" class="account-form" id="login_form_order_page">

                    <div class="error-wrap"></div>
                        <div class="col-lg-12 col-md-12">
                            <div class="input-form input-form2">
                                <input type="text" name="username" placeholder="{{__('Enter your username or email')}}" value="{{$username}}">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="input-form input-form2">
                                <input type="password" name="password" value="{{$password}}" placeholder="{{__('Password')}}">
                            </div>
                        </div>
                        <!--Forget passwords -->
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
                                <button type="submit" class="cmn-btn1 w-100 mb-40" id="login_btn">{{__('Login')}}</button>

                                <p class="sinUp mb-20"><span>{{__('Don’t have an account ?')}} </span>
                                    <a href="{{route('landlord.user.register')}}" class="singApp">{{__('Sign Up')}}</a></p>

                                @if(!empty(get_static_option('social_google_status')))
                                    <a href="{{route('landlord.login.google.redirect')}}" class="cmn-btn-outline0  mb-20 w-100">
                                        <img src="{{asset('assets/landlord/frontend/img/icon/googleIocn.svg')}}" alt="image" class="icon">
                                        {{__('Login With Google')}}
                                    </a>
                                @endif

                                @if(!empty(get_static_option('social_facebook_status')))
                                    <a href="{{route('landlord.login.facebook.redirect')}}" class="cmn-btn-outline0 mb-20  w-100">
                                      <img src="{{asset('assets/landlord/frontend/img/icon/fbIcon.svg')}}" alt="image" class="icon">
                                        {{__('Login With Facebook')}}
                                    </a>
                                 @endif

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
   <x-custom-js.ajax-login/>
@endsection
