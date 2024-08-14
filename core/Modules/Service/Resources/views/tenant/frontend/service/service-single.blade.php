@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();

@endphp

@section('page-title')
    {{ $service->getTranslation('title',$user_lang) }}
@endsection

@section('title')
    {{ $service->getTranslation('title',$user_lang) }}
@endsection

@section('meta-data')
    {!!  render_page_meta_data($service) !!}
@endsection

@section('content')

<section class="servicesArea section-padding">
<div class="container">
    <div class="row">
        <div class="col-xxl-4 col-xl-4 col-lg-5 col-md-6">
            <x-service::frontend.sidebar-data/>
        </div>
        <div class="col-xxl-8 col-xl-8 col-lg-7 col-md-6">

    <article class="servicesDiscription-global">

        <div class="capImg imgEffect">
            {!! render_image_markup_by_attachment_id($service->image) !!}
        </div>
    </article>

    <article class="serviceProvide mb-30">
       {!! $service->getTranslation('description',get_user_lang()) !!}
    </article>


    <!--Related Service  -->
    <div class="row">
        <div class="col-xl-12">
            <div class="section-tittle mb-30">
                <h2 class="tittle">{{__('Related Service')}}</h2>
            </div>
        </div>

        @foreach($all_related_services as $rel)
             <div class="col-lg-6">
            <figure class="singleServices-global mb-24">
                <div class="event-img imgEffect">
                    {!! render_image_markup_by_attachment_id($rel->image) !!}
                </div>
                <figcaption class="eventCaption">
                    <h3><a href="{{route('tenant.frontend.service.single',$rel->slug)}}" class="tittle">{!! $rel->getTranslation('title',get_user_lang()) !!}</a></h3>
                    <p class="pera">{!! \Illuminate\Support\Str::words(purify_html_raw($rel->getTranslation('description',get_user_lang())),13) !!}</p>
                    <div class="btn-wrapper mb-20 mt-20">
                        @php
                            $d_theme = get_static_option('tenant_default_theme');
                            $theme_con = $d_theme == 'software-business' ? 'softwareFirm_cmn_btn btn_bg_1' : $d_theme.'_cmn_btn btn_bg_1';
                        @endphp
                        <a href="{{route('tenant.frontend.service.single',$rel->slug)}}" class="{{$theme_con}} cmn-btn-outline3"> {{__('Explore')}} </a>
                    </div>
                </figcaption>
            </figure>
        </div>
        @endforeach
    </div>
</div>

    </div>
</div>
</section>
@endsection
