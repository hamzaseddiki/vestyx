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

    <link href="{{ global_asset('assets/landlord/admin/css/materialdesignicons.min.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/landlord/admin/css/vendor.bundle.base.css') }}" rel="stylesheet">
    <link href="{{ global_asset('assets/landlord/admin/css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
           @yield('content')
        </div>
    </div>
</div>


<script src="{{global_asset('assets/landlord/admin/js/vendor.bundle.base.js')}}"></script>
<script src="{{global_asset('assets/landlord/admin/js/hoverable-collapse.js')}}"></script>
<script src="{{global_asset('assets/landlord/admin/js/off-canvas.js')}}"></script>
<script src="{{global_asset('assets/landlord/admin/js/misc.js')}}"></script>
<script src="{{global_asset('assets/landlord/common/js/axios.min.js')}}"></script>
@yield('scripts')
</body>
</html>
