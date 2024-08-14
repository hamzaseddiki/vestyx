@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('page-title')
    {{ $activites->getTranslation('title',$user_lang) }}
@endsection

@section('title')
    {{ $activites->getTranslation('title',$user_lang) }}
@endsection

@section('content')
    <div class="detailsCapTow section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <!-- Single -->
                    <div class="capDetails2  mb-10">
                        <div class="blog-img overlay1">
                            {!! render_image_markup_by_attachment_id($activites->image) !!}
                        </div>
                        <div class="caption">
                            <ul class="postInfo mb-30">
                                <li class="listItmes"><i class="fa-regular fa-clock icon"></i>{{ $activites->created_at?->diffForHumans() }}</li>
                                <li class="listItmes"><i class="fa-solid fa-tag icon"></i> {{$activites->category?->getTranslation('title',get_user_lang())}}</li>
                            </ul>
                            <p class="pera">
                            {!! $activites->getTranslation('description',get_user_lang()) !!}
                            </p>

                        </div>

                    </div>

                </div>
                <div class="col-lg-5">
                    {!! render_frontend_sidebar('donation_acitvities_sidebar',['column' => false]) !!}
                </div>
            </div>
        </div>
    </div>

@endsection
