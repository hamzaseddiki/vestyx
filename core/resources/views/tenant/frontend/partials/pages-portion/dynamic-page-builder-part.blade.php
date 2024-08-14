@php
    if(!isset($page_post)){
        return;
    }
@endphp
{!! \Plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page',$page_post->id) !!}



