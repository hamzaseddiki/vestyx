@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Third Party Scripts Settings')}} @endsection

@section('content')

    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Third Party Scripts Settings")}}</h4>
                        <form action="{{route(route_prefix().'admin.general.third.party.script.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                            @if(!tenant())
                                <div class="form-group">
                                    <label for="site_third_party_tracking_code_just_after_head">{{__('Third Party Api Code')}} {{__("just after <head> tag")}}</label>
                                    <textarea name="site_third_party_tracking_code_just_after_head"  cols="30" rows="10" class="form-control">{{get_static_option('site_third_party_tracking_code_just_after_head')}}</textarea>
                                    <p>{{__('this code will be load just after <head> tag')}}</p>
                                </div>

                                <div class="form-group">
                                    <label for="site_third_party_tracking_code">{{__('Third Party Api Code')}} {{__("just before </head> tag")}}</label>
                                    <textarea name="site_third_party_tracking_code" id="site_third_party_tracking_code" cols="30" rows="10" class="form-control">{{get_static_option('site_third_party_tracking_code')}}</textarea>
                                    <p>{{__('this code will be load just before </head> tag')}}</p>
                                </div>
                                <div class="form-group">
                                    <label for="site_third_party_tracking_code_just_after_body">{{__('Third Party Api Code')}} {{__("just after <body> tag")}}</label>
                                    <textarea name="site_third_party_tracking_code_just_after_body" cols="30" rows="10" class="form-control">{{get_static_option('site_third_party_tracking_code_just_after_body')}}</textarea>
                                    <p>{{__('this code will be load just after <body> tag')}}</p>
                                </div>
                                <div class="form-group">
                                    <label for="site_third_party_tracking_code_just_before_body_close">{{__('Third Party Api Code')}} {{__("just before </body> tag")}}</label>
                                    <textarea name="site_third_party_tracking_code_just_before_body_close"cols="30" rows="10" class="form-control">{{get_static_option('site_third_party_tracking_code_just_before_body_close')}}</textarea>
                                    <p>{{__('this code will be load before </body> tag')}}</p>
                                </div>
                            @endif


                            <div class="form-group">
                                <label for="site_google_analytics">{{__('Google Analytics')}}</label>
                                <textarea type="text" name="site_google_analytics"  class="form-control" cols="30" rows="10"  id="site_google_analytics">{!! get_static_option('site_google_analytics') !!}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="site_google_captcha_v3_site_key">{{__('Google Captcha V3 Site Key')}}</label>
                                <input type="text" name="site_google_captcha_v3_site_key"  class="form-control" value="{{get_static_option('site_google_captcha_v3_site_key')}}" id="site_google_captcha_v3_site_key">
                            </div>
                            <div class="form-group">
                                <label for="site_google_captcha_v3_secret_key">{{__('Google Captcha V3 Secret Key')}}</label>
                                <input type="text" name="site_google_captcha_v3_secret_key"  class="form-control" value="{{get_static_option('site_google_captcha_v3_secret_key')}}" id="site_google_captcha_v3_secret_key">
                            </div>

                            <x-fields.switcher value="{{get_static_option('social_facebook_status')}}" name="social_facebook_status" label="{{__('Enable/Disable Facebook Login')}}"/>

                            <div class="form-group">
                                <label for="facebook_client_id">{{__('Facebook Client ID')}}</label>
                                <input type="text" name="facebook_client_id"  class="form-control" value="{{get_static_option('facebook_client_id')}}">
                            </div>
                            <div class="form-group">
                                <label for="facebook_client_secret">{{__('Facebook Client Secret')}}</label>
                                <input type="text" name="facebook_client_secret"  class="form-control" value="{{get_static_option('facebook_client_secret')}}">
                            </div>
                            <p class="info-text">{{__('facebook callback url for your app')}} <code>{{url('/')}}/facebook/callback</code>
                                <a href="https://bytesed.com/docs/facebook-login/" target="_blank"><i class="fas fa-external-link-alt"></i></a></p>


                            <x-fields.switcher value="{{get_static_option('social_google_status')}}" name="social_google_status" label="{{__('Enable/Disable Google Login')}}"/>
                            <div class="form-group">
                                <label for="google_client_id">{{__('Google Client ID')}}</label>
                                <input type="text" name="google_client_id"  class="form-control" value="{{get_static_option('google_client_id')}}">
                            </div>
                            <div class="form-group">
                                <label for="google_client_secret">{{__('Google Client Secret')}}</label>
                                <input type="text" name="google_client_secret"  class="form-control" value="{{get_static_option('google_client_secret')}}">
                            </div>
                            <p class="info-text">{{__('google callback url for your app')}} <code>{{url('/')}}/google/callback</code>
                                <a href="https://bytesed.com/docs/google-login/" target="_blank"><i class="fas fa-external-link-alt"></i></a></p>


                            @if(tenant())
                            <div class="form-group mt-4">
                                <label for="tawk_api_key">{{__('Google Adsense Publisher ID')}}</label>
                                <input type="text" name="google_adsense_publisher_id"  class="form-control" value="{{get_static_option('google_adsense_publisher_id')}}" id="google_adsense_id">
                            </div>
                            <div class="form-group">
                                <label for="tawk_api_key">{{__('Google Adsense Customer ID')}}</label>
                                <input type="text" name="google_adsense_customer_id"  class="form-control" value="{{get_static_option('google_adsense_customer_id')}}" id="google_adsense_id">
                            </div>
                            @endif

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
