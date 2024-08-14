@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Topbar Settings')}} @endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Topbar Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.topbar.settings')}}">
                    @csrf
                    <x-fields.input name="topbar_phone" value="{{get_static_option('topbar_phone')}}" label="{{__('Phone')}}"/>
                    <x-fields.input name="topbar_email" value="{{get_static_option('topbar_email')}}" label="{{__('Email')}}"/>

                    <x-fields.input name="topbar_facebook_url" value="{{get_static_option('topbar_facebook_url')}}" label="{{__('Facebook URL')}}"/>
                    <x-fields.input name="topbar_instagram_url" value="{{get_static_option('topbar_instagram_url')}}" label="{{__('Instagram URL')}}"/>
                    <x-fields.input name="topbar_linkedin_url" value="{{get_static_option('topbar_linkedin_url')}}" label="{{__('Linkedin URL')}}"/>
                    <x-fields.input name="topbar_twitter_url" value="{{get_static_option('topbar_twitter_url')}}" label="{{__('Twitter URL')}}"/>

                    @if(tenant())
                       <x-fields.input name="topbar_youtube_url" value="{{get_static_option('topbar_youtube_url')}}" label="{{__('Youtube URL')}}"/>
                    @endif

                    <x-fields.switcher name="landlord_frontend_contact_info_show_hide" value="{{get_static_option('landlord_frontend_contact_info_show_hide')}}"  label="{{__('Enable/Disable Contact Info')}}"/>
                    <x-fields.switcher name="landlord_frontend_language_show_hide" value="{{get_static_option('landlord_frontend_language_show_hide')}}"  label="{{__('Enable/Disable Language')}}"/>
                    <x-fields.switcher name="landlord_frontend_social_info_show_hide" value="{{get_static_option('landlord_frontend_social_info_show_hide')}}"  label="{{__('Enable/Disable Social Info')}}"/>
                    <x-fields.switcher name="landlord_frontend_topbar_show_hide" value="{{get_static_option('landlord_frontend_topbar_show_hide')}}"  label="{{__('Enable/Disable Full Topbar')}}"/>

                    @if(tenant())
                        <x-fields.switcher name="tenant_login_show_hide" value="{{get_static_option('tenant_login_show_hide')}}"  label="{{__('Enable/Disable Login Topbar')}}"/>
                        <x-fields.switcher name="tenant_register_show_hide" value="{{get_static_option('tenant_register_show_hide')}}"  label="{{__('Enable/Disable Register Topbar')}}"/>
                    @endif

                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
