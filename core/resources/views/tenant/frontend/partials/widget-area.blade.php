
@php
    $current_theme_slug = getSelectedThemeSlug();
    $widget_area_name = getFooterWidgetArea();
    $footer_view = 'themes.'.$current_theme_slug.'.footerWidgetArea.'.$widget_area_name;

   $tenant_default_theme = get_static_option('tenant_default_theme');
    $landlord_default_theme_set = get_static_option_central('landlord_default_theme_set');
    $condition = $tenant_default_theme ?? $landlord_default_theme_set;
@endphp

@if(View::exists($footer_view))
    @include($footer_view)
@else
    @include('tenant.frontend.partials.pages-portion.footers.footer-'. $condition)
@endif
