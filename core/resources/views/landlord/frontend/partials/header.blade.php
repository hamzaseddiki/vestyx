<!DOCTYPE html>
<html dir="{{ \App\Facades\GlobalLanguage::user_lang_dir() }}" lang="{{ \App\Facades\GlobalLanguage::user_lang_slug() }}">
<head>
	<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 

    
    {!! get_static_option('site_third_party_tracking_code_just_after_head') !!}
    {!! get_static_option('site_google_analytics') !!}

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @if(request()->is('home') || request()->is('/'))
    <meta property="title" content="{{ get_static_option('site_'.get_user_lang().'_meta_title') }}" />
    <meta property="tags" content="{{ get_static_option('site_'.get_user_lang().'_meta_tags') }}" />
    <meta property="keywords" content="{{ get_static_option('site_'.get_user_lang().'_meta_keywords') }}" />
    <meta property="description" content="{{ get_static_option('site_'.get_user_lang().'_meta_description') }}" />

    @php
        $og_meta_image = get_attachment_image_by_id(get_static_option('site_'.get_user_lang().'_og_meta_image'));
    @endphp
    <meta property="og:title" content="{{ get_static_option('site_'.get_user_lang().'_og_meta_title') }}" />
    <meta property="og:description" content="{{ get_static_option('site_'.get_user_lang().'_og_meta_description') }}" />
    <meta property="og:image" content="{{$og_meta_image['img_url'] ?? ''}}" />
    @endif

    @if(Route::currentRouteName() === 'landlord.dynamic.page')
        {!!  render_page_meta_data($page_post) !!}
    @else
        @yield('meta-data')
    @endif

    {!! SEOMeta::generate() !!}
    {!! JsonLd::generate() !!}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>


    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}

    @php
        $static_page_meta_data = get_static_option('site_'.get_user_lang().'_meta_title');
    @endphp

    <title>
        @if(!request()->routeIs('landlord.homepage'))
            @if(Route::currentRouteName() === 'landlord.dynamic.page')
                @php
                    $dynamic_page_title = $page_post->getTranslation('title',get_user_lang());
                    $static_page_meta_data = $page_post->metainfo?->getTranslation('title',get_user_lang());
                    $condition_other_dynamic_pages = !empty($static_page_meta_data) ? $static_page_meta_data : $dynamic_page_title;
                @endphp
                {{$condition_other_dynamic_pages}}
            @else
                @yield('title')
            @endif
            -
            {{get_static_option('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_title')}}
        @else

            @php
                $site_meta_tittle = !empty($page_post) ? $page_post->metainfo?->getTranslation('title',get_user_lang()) : '';
                $site_page_tittle = !empty($page_post) ? $page_post->getTranslation('title',get_user_lang()) : '';
                $condition_meta_title = !empty($site_meta_tittle) ? $site_meta_tittle : $site_page_tittle;
            @endphp

              {{ $condition_meta_title ?? get_static_option('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_title')}}

            @if(!empty(get_static_option('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_tag_line')))
                - {{get_static_option('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_tag_line')}}
            @endif
        @endif
    </title>

    <link rel="stylesheet" href="{{asset('assets/landlord/frontend/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/landlord/frontend/css/plugin.css')}}">

    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/custom-dashboard.css')}}">
    <link rel="stylesheet" href="{{asset('assets/landlord/frontend/css/main-style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/landlord/common/css/helpers.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/common/css/jquery.ihavecookies.css')}}">
    <link rel="stylesheet" href="{{asset('assets/landlord/frontend/css/developer.css')}}">
    <link rel="stylesheet" href="{{asset('assets/landlord/frontend/css/dynamic-style.css')}}">

    @if(\App\Facades\GlobalLanguage::user_lang_dir() == 'rtl')
        <link rel="stylesheet" href="{{asset('assets/landlord/frontend/css/rtl.css')}}">
    @endif

    @include('landlord.frontend.partials.font-manage')
    @include('landlord.frontend.partials.color-font-variable')

    @yield('style')

    @yield('seo_data')
    {!! get_static_option('site_third_party_tracking_code') !!}
</head>
<body>
{!! get_static_option('site_third_party_tracking_code_just_after_body') !!}
@include('landlord.frontend.partials.navbar')

