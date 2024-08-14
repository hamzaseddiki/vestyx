
<!DOCTYPE html>
<html lang="{{ \App\Facades\GlobalLanguage::user_lang_slug() }}" dir="{{ \App\Facades\GlobalLanguage::user_lang_dir() }}">

<head>

    {!! get_static_option('site_google_analytics') !!}

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    @if(Route::currentRouteName() === 'tenant.dynamic.page')
        {!!  render_page_meta_data($page_post) !!}
    @else
        @yield('meta-data')
    @endif

    {!! SEOMeta::generate() !!}
    {!! JsonLd::generate() !!}


    @php
        $theme_slug = getSelectedThemeSlug();
        $theme_header_css_files = \App\Facades\ThemeDataFacade::getHeaderHookCssFiles();
        $theme_header_js_files = \App\Facades\ThemeDataFacade::getHeaderHookJsFiles();
        $theme_header_rtl_css = \App\Facades\ThemeDataFacade::getHeaderHookRtlCssFiles();
    @endphp

    {{--Custom and Google Font Manage--}}
       @include('tenant.frontend.partials.font-manage')
    {{--Custom and Google Font Manage--}}

    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}


    <title>
        @if(!request()->routeIs('tenant.frontend.homepage'))
            @if(Route::currentRouteName() === 'tenant.dynamic.page')
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
            {{filter_static_option_value('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_title',$global_static_field_data)}}
        @else

            @php
                $site_meta_tittle = !empty($page_post) ? $page_post->metainfo?->getTranslation('title',get_user_lang()) : '';
                $site_page_tittle = !empty($page_post) ? $page_post->getTranslation('title',get_user_lang()) : '';
                $condition_meta_title = !empty($site_meta_tittle) ? $site_meta_tittle : $site_page_tittle;
            @endphp

            {{ !empty($condition_meta_title) ? $condition_meta_title : filter_static_option_value('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_title',$global_static_field_data)}}

            @if(!empty(filter_static_option_value('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_tag_line',$global_static_field_data)))
                - {{filter_static_option_value('site_'.\App\Facades\GlobalLanguage::user_lang_slug().'_tag_line',$global_static_field_data)}}
            @endif
        @endif
    </title>


    {!! render_favicon_by_id(filter_static_option_value('site_favicon', $global_static_field_data)) !!}

    @php
        $loadCoreStyle = loadCoreStyle();
    @endphp

    @if(in_array('bootstrap', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/themes/css/bootstrap.css')}}">
    @endif

    @if(in_array('plugin', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/themes/css/plugin.css')}}">
    @endif

    @if(in_array('toastr', $loadCoreStyle))
        <link rel="stylesheet" href="{{ global_asset('assets/common/css/toastr.css') }}">
    @endif

    @if(in_array('odometer', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/themes/css/odometer.css')}}">
    @endif

    @if(in_array('developer', $loadCoreStyle))
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/themes/css/developer.css')}}">
    @endif

   @if(!empty(tenant()->id) && file_exists('assets/tenant/frontend/themes/css/dynamic-styles/'.tenant()->id.'-style.css') && !is_dir('assets/tenant/frontend/themes/css/dynamic-styles/'.tenant()->id.'-style.css'))
        {{--Do not show this dynamic css file if empty--}}
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/themes/css/dynamic-styles/'.tenant()->id.'-style.css')}}">
    @endif

    <link rel="stylesheet" href="{{global_asset('assets/landlord/common/css/helpers.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/common/css/jquery.ihavecookies.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/common/css/magnific-popup.css')}}">
    <x-frontend.common-css/>

    @foreach($theme_header_css_files ?? [] as $cssFile)
        <link rel="stylesheet" href="{{ loadCss($cssFile) }}" type="text/css">
    @endforeach


    @if(\App\Facades\GlobalLanguage::user_lang_dir() == 'rtl')
        @foreach($theme_header_rtl_css ?? [] as $cssFile)
            <link rel="stylesheet" href="{{ loadCss($cssFile) }}" type="text/css">
        @endforeach
    @endif

    @include('tenant.frontend.partials.css-variable')

    <x-loaders.custom-loader/>

    @yield('style')

    @foreach($theme_header_js_files ?? [] as $jsFile)
        <script src="{{loadJs($jsFile)}}"></script>
    @endforeach
</head>


<body class="{{tenant()?->theme_slug}}">

@include('tenant.frontend.partials.navbar')

