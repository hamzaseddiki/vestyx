@extends('landlord.frontend.frontend-page-master')

@section('title')
    {{__('Register')}}
@endsection

@section('page-title')
    {{__('Register')}}
@endsection

@section('style')
    <style>
        .nice-select {
            position: relative;
            z-index: 8;
            margin-bottom: 20px;
        }
        .nice-select:focus {
            outline: none;
            box-shadow: unset;
        }

        .loginArea .login-Wrapper .input-form .phone{
            padding-left: 15px;
        }

    </style>
@endsection

@section('content')
    @php
        $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
        $countries = \Modules\CountryManage\Entities\Country::select('id','name')->get();
        $title = get_static_option('landlord_user_register_'.$current_lang.'_title');

        if (str_contains($title, '{h}') && str_contains($title, '{/h}'))
        {
            $text = explode('{h}',$title);
            $highlighted_word = explode('{/h}', $text[1])[0];
            $highlighted_text = '<span class="color">'. $highlighted_word .'</span>';
            $final_title = '<h2 class="tittle wow fadeInUp" data-wow-delay="0.0s">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $title).'</h2>';
        } else {
            $final_title = '<h2 class="tittle wow fadeInUp" data-wow-delay="0.0s">'. $title .'</h2>';
        }

            $feature_show_hide_con = empty(get_static_option('landlord_frontend_register_feature_show_hide')) ? 'section-padding' : '';
    @endphp

    @if(!empty(get_static_option('landlord_frontend_register_feature_show_hide')))
        <section class="categoriesArea section-bg section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="singleCat text-center mb-24">
                        <div class="cat-icon">
                            {!! render_image_markup_by_attachment_id(get_static_option('landlord_user_register_feature_image_one')) !!}
                        </div>
                        <div class="cat-cap">
                            <h5><a href="#" class="tittle">{{ get_static_option('landlord_user_register_feature_'.$current_lang.'_title_one') }}</a></h5>
                            <p class="pera">{{get_static_option('landlord_user_register_feature_'.$current_lang.'_description_one')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="singleCat text-center mb-24">
                        <div class="cat-icon">
                            {!! render_image_markup_by_attachment_id(get_static_option('landlord_user_register_feature_image_two')) !!}
                        </div>
                        <div class="cat-cap">
                            <h5><a href="#" class="tittle">{{get_static_option('landlord_user_register_feature_'.$current_lang.'_title_two')}}</a></h5>
                            <p class="pera">{{get_static_option('landlord_user_register_feature_'.$current_lang.'_description_two')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="singleCat text-center mb-24">
                        <div class="cat-icon">
                            {!! render_image_markup_by_attachment_id(get_static_option('landlord_user_register_feature_image_three')) !!}
                        </div>
                        <div class="cat-cap">
                            <h5><a href="#" class="tittle">{{get_static_option('landlord_user_register_feature_'.$current_lang.'_title_three')}}</a></h5>
                            <p class="pera">{{get_static_option('landlord_user_register_feature_'.$current_lang.'_description_three')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif


    <div class="loginArea bottom-padding {{$feature_show_hide_con}} register_page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-7 col-lg-9 login-Wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-tittle section-tittle2 text-center mb-30">
                              {!! $final_title !!}
                            </div>
                        </div>
                        <x-error-msg/>
                        <x-flash-msg/>
                        <form action="{{route('landlord.user.register.store')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                            @csrf

                        <div class="col-lg-12 col-md-12">
                            <div class="input-form input-form2">
                                <input type="text" name="name" placeholder="{{__('Name')}}" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="input-form input-form2">
                                <input type="text" name="username" placeholder="{{__('Username')}}" value="{{ old('username') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-form input-form2">
                                <input type="text" name="email" placeholder="{{__('Email')}}" value="{{ old('email') }}">
                            </div>
                        </div>
                        <!-- country Number Selector -->

                        <div class="col-lg-12">
                            <div class="input-form">
                                 <input name="mobile" type="number" placeholder="{{__('Phone')}}" class="phone" value="{{ old('mobile') }}">
                            </div>
                        </div>

                            <div class="col-md-12">
                                <div class="input-form input-form2">
                                    <input type="text" name="address" placeholder="{{__('Address')}}" value="{{ old('address') }}">
                                </div>
                            </div>

                        <div class="col-md-12">
                            <select name="country" class="form-control register_countries niceSelect_active">
                                <option disabled="" selected>{{__('Select a country')}}</option>
                                 @foreach($countries as $country)
                                   <option value="{{$country->id}}">{{$country->name}}</option>
                                 @endforeach
                            </select>
                        </div>

                            <div class="col-md-12">
                                <div class="input-form input-form2">
                                    <input type="password" name="password" placeholder="{{__('password')}}" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-form input-form2">
                                    <input type="password" name="password_confirmation" placeholder="{{__('Confirm Password')}}">
                                </div>
                            </div>


                        <div class="col-sm-12">
                            <div class="btn-wrapper text-center mt-20">
                                <button type="submit" class="cmn-btn1 w-100 mb-40">{{__('Register')}}</button>

                                <p class="sinUp mb-20"><span>{{__('Already have an account')}}?</span>
                                    <a href="{{route('landlord.user.login')}}" class="singApp">{{__('Login')}}</a>
                                </p>

                                @if(!empty(get_static_option('landlord_frontend_login_google_show_hide')))
                                <a href="{{route('landlord.login.google.redirect')}}" class="cmn-btn-outline0  mb-20 w-100">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/googleIocn.svg')}}" alt="image" class="icon">{{__('Register With Google')}}</a>
                                @endif

                                @if(!empty(get_static_option('landlord_frontend_login_facebook_show_hide')))
                                <a href="{{route('landlord.login.facebook.redirect')}}" class="cmn-btn-outline0 mb-20 w-100">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/fbIcon.svg')}}" alt="image" class="icon">{{__('Register With Facebook')}}</a>
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
    <script>
        (function($){
            "use strict";
            $(document).ready(function () {
                <x-btn.custom :id="'register'" :title="__('Please Wait..')"/>
            });
        })(jQuery);
    </script>
@endsection
