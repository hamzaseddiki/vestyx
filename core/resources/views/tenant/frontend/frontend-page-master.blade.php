@include('tenant.frontend.partials.header')

@php
    $user_lang = get_user_lang();
    $default_theme = get_static_option('tenant_default_theme') ?? tenant()->theme_slug;
    $breadcrumb_area_name = getHeaderBreadcrumbArea();
    $breadcrumb_view = 'themes.'.$default_theme.'.headerBreadcrumbArea.'.$breadcrumb_area_name;
@endphp

@if(!empty(getHeaderBreadcrumbArea()))
    @include($breadcrumb_view, ['user_lang' => $user_lang])
@endif

{!! \App\Facades\ThemeDataFacade::renderHeaderHookBladeFile() !!}

@yield('content')
@include('tenant.frontend.partials.footer')
