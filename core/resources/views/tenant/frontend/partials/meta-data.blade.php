@php
    $user_language_slug = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp
<meta name ="author" content= "{{filter_static_option_value('site_'.$user_language_slug.'_title',$global_static_field_data)}}" />
<meta name ="tags" content= "{{filter_static_option_value('site_'.$user_language_slug.'_meta_tags',$global_static_field_data)}}" />
<meta name ="keywords" content= "{{filter_static_option_value('site_'.$user_language_slug.'_meta_keywords',$global_static_field_data)}}" />
<meta name ="description" content= "{{filter_static_option_value('site_'.$user_language_slug.'_meta_description',$global_static_field_data)}}." />


{{-- OG Meta Tags --}}
<meta property="og:type" content="article" />

<meta property="og:title" content="{{filter_static_option_value('site_'.$user_language_slug.'_og_meta_title',$global_static_field_data)}}" />

<meta property="og:description" content="{{filter_static_option_value('site_'.$user_language_slug.'_og_meta_description',$global_static_field_data)}}" />
@php
    $image_url = '';
    $image_details = get_attachment_image_by_id(filter_static_option_value('site_'.$user_language_slug.'_og_meta_image',$global_static_field_data));
    if (isset($image_details['img_url'])){
        $image_url = $image_details['img_url'];
    }
@endphp
<meta property="og:image" content="{{$image_url}}" />

<meta property="og:url" content="{{\Illuminate\Support\Facades\URL::current()}}" />

<meta property="og:site_name" content="{{filter_static_option_value('site_'.$user_language_slug.'_title',$global_static_field_data)}}" />

{{-- Twitter og meta --}}

<meta name="twitter:title" content="{{filter_static_option_value('site_'.$user_language_slug.'_og_meta_title',$global_static_field_data)}}">

<meta name="twitter:description" content="{{filter_static_option_value('site_'.$user_language_slug.'_og_meta_description',$global_static_field_data)}}">

<meta name="twitter:image" content="{{$image_url}}">
