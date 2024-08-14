@if(get_static_option('landlord_frontend_topbar_show_hide'))

  @php
      $theme = get_static_option('tenant_default_theme');
      $condition_for_portfolio_inner = $theme == 'portfolio' && !request()->is('/') ? 'portfolio_inner_topbar' : '';
  @endphp

<div class="header-top agency_topbar {{$theme}} {{$condition_for_portfolio_inner}}">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="d-flex justify-content-between flex-wrap align-items-center">
                    <div class="header-info-left">
                        @if(!empty(get_static_option('landlord_frontend_contact_info_show_hide')))
                        <ul class="listing">
                            <li class="listItem"><i class="fa-solid fa-phone icon"></i>{{get_static_option('topbar_phone')}}</li>
                            <li class="listItem"><i class="fa-solid fa-envelope icon"></i> {{get_static_option('topbar_email')}}</li>
                        </ul>
                            @endif
                    </div>
                    <div class="header-info-right">

                        <ul class="user-account">
                            @if (auth()->check())
                                @php
                                    $route = auth()->guest() == 'admin' ? route('tenant.admin.dashboard') : route('tenant.user.home');
                                @endphp
                                <li class="listItem"><a href="{{ $route }}">{{ __('Dashboard') }}</a> <span>/</span>
                                    <a href="{{ route('tenant.user.logout') }}">{{ __('Logout') }}</a>
                                </li>
                            @else

                                <li class="listItem">
                                    @if(!empty(get_static_option('tenant_login_show_hide')))
                                     <a href="{{ route('tenant.user.login') }}">{{ __('Login') }}</a>
                                        <span>/</span>
                                    @endif
                                        @if(!empty(get_static_option('tenant_register_show_hide')))
                                               <a href="{{ route('tenant.user.register') }}">{{ __('Register') }}</a>
                                        @endif
                                </li>
                            @endif
                        </ul>
                        <div class="language_dropdown @if(get_user_lang_direction() == 'rtl') ml-1 @else mr-1 @endif d-none" id="languages_selector">
                            @if (auth()->check())
                                @php
                                    $route = auth()->guest() == 'admin' ? route('tenant.admin.dashboard') : route('tenant.user.home');
                                @endphp
                                <div class="selected-language">{{ __('Account') }}<i class="fas fa-caret-down"></i></div>
                                <ul>
                                    <li class="listItem"><a href="{{ $route }}">{{ __('Dashboard') }}</a>
                                    <li class="listItem"><a href="{{ route('tenant.user.logout') }}">{{ __('Logout') }}</a></li>
                                </ul>
                            @else
                                <div class="selected-language">{{ __('Login') }}<i class="fas fa-caret-down"></i></div>
                                <ul>
                                    <li class="listItem"><a class="listItem" href="{{ route('tenant.user.login') }}">{{ __('Login') }}</a>
                                    <li class="listItem"><a class="listItem" href="{{ route('tenant.user.register') }}">{{ __('Register') }}</a>
                                </ul>
                            @endif
                        </div>
                        @if(get_static_option('landlord_frontend_language_show_hide'))
                        <!-- Select  -->
                        <div class="select-language">
                            <select class="niceSelect tenant_languages_selector">
                                @foreach(\App\Facades\GlobalLanguage::all_languages(\App\Enums\StatusEnums::PUBLISH) as $lang)
                                    @php
                                        $exploded = explode('(',$lang->name);
                                    @endphp
                                   <option class="lang_item" value="{{$lang->slug}}" >{{current($exploded)}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @if(!empty(get_static_option('landlord_frontend_social_info_show_hide')))
                            <ul class="header-cart">
                                @if(!empty(get_static_option('topbar_facebook_url')))
                                    <li class="listItem"><a href="{{get_static_option('topbar_facebook_url')}}" class="social"><i class="lab la-facebook-f icon"></i></a></li>
                                @endif

                                @if(!empty(get_static_option('topbar_instagram_url')))
                                    <li class="listItem"> <a href="{{get_static_option('topbar_instagram_url')}}" class="social"><i class="lab la-instagram icon"></i></a></li>
                                @endif


                                @if(!empty(get_static_option('topbar_linkedin_url')))
                                    <li class="listItem"> <a href="{{get_static_option('topbar_linkedin_url')}}" class="social"><i class="lab la-linkedin-in icon"></i></a></li>
                                @endif

                                @if(!empty(get_static_option('topbar_twitter_url')))
                                    <li class="listItem"> <a href="{{get_static_option('topbar_twitter_url')}}" class="social"><i class="lab la-twitter icon"></i></a></li>
                                @endif

                                @if(!empty(get_static_option('topbar_youtube_url')))
                                    <li class="listItem"> <a href="{{get_static_option('topbar_youtube_url')}}" class="social"><i class="lab la-youtube icon"></i></a></li>
                                @endif
                            </ul>
                         @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
