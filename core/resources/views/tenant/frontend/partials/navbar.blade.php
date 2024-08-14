@php
    $current_theme_slug = getSelectedThemeSlug();
    $navbar_area_name = getHeaderNavbarArea();

    $navbar_view = 'themes.'.$current_theme_slug.'.headerNavbarArea.'.$navbar_area_name;

    $tenant_default_theme = get_static_option('tenant_default_theme');
    $landlord_default_theme_set = get_static_option_central('landlord_default_theme_set');
    $condition = $tenant_default_theme ?? $landlord_default_theme_set;
@endphp

@if(View::exists($navbar_view))
    @include($navbar_view)
@else
    @include('tenant.frontend.partials.pages-portion.navbars.navbar-'.$condition)
@endif
