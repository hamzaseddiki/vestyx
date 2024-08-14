
        @if(!empty(get_static_option('social_google_status')))
        <a href="{{ route('tenant.login.google.redirect') }}" class="cmn-btn-outline0  mb-20 w-100">
            <img src="{{global_asset('assets/tenant/frontend/img/icon/googleIocn.svg')}}" alt="" class="icon">{{__('Login With Google')}}
        </a>
        @endif

        @if(!empty(get_static_option('social_facebook_status')))
        <a href="{{ route('tenant.login.facebook.redirect') }}" class="cmn-btn-outline0 mb-20  w-100">
            <img src="{{global_asset('assets/tenant/frontend/img/icon/fbIcon.svg')}}" alt="" class="icon">{{__('Login With Facebook')}}
        </a>
        @endif
