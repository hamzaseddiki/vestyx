<!doctype html>

<html lang="{{ \App\Facades\GlobalLanguage::default_slug() }}" dir="{{ \App\Facades\GlobalLanguage::default_dir() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if(!request()->routeIs('landlord.admin.home'))
            @yield('title')  -
        @endif
        {{get_static_option('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_title',__('Xgenious'))}}
        @if(!empty(get_static_option('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_tag_line')))
            - {{get_static_option('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_tag_line')}}
        @endif
    </title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}
    <!-- Styles -->
    <link href="{{ global_asset('assets/landlord/admin/css/materialdesignicons.min.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/landlord/admin/css/vendor.bundle.base.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/landlord/admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/common/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/common/css/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/common/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/common/css/line-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/landlord/admin/css/nice-select.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/common/css/toastr.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/common/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{global_asset('assets/common/css/custom-style.css')}}">


    <!-- dark mode css  -->
    @if(!empty(get_static_option('dark_mode_for_admin_panel')))
        <link href="{{ global_asset('assets/landlord/admin/css/dark-mode.css') }}" rel="stylesheet">
    @endif

    @if(\App\Facades\GlobalLanguage::default_dir() == 'rtl')
        <link href="{{ global_asset('assets/landlord/admin/css/rtl.css') }}" rel="stylesheet">
    @endif


    @yield('style')
</head>
<body>


<div class="container-scroller">
    @include('landlord.admin.partials.topbar')
    <div class="container-fluid page-body-wrapper">
@include('landlord.admin.partials.sidebar')
