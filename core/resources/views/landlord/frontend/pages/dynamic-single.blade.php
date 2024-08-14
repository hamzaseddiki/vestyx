@extends('landlord.frontend.frontend-page-master')
@section('title')
    {!! $page_post->getTranslation('title',\App\Facades\GlobalLanguage::user_lang_slug()) !!}
@endsection
@section('page-title')
    {!! $page_post->getTranslation('title',\App\Facades\GlobalLanguage::user_lang_slug()) !!}
@endsection

@section('seo_data')
    {!! SEOMeta::generate() !!}
@endsection

@section('content')
    @if($page_post->page_builder === 1)
        @if( $page_post->visibility === 1)
            @if(auth('web')->check())
                @include('landlord.frontend.partials.pages-portion.dynamic-page-builder-part',['page_post' => $page_post])
            @else
                <section class="padding-top-100 padding-bottom-100">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-warning">
                                    <p><a class="text-primary" href="{{route('login')}}">{{__('Login')}}</a> {{__(' to see this page')}} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @else
            @include('landlord.frontend.partials.pages-portion.dynamic-page-builder-part',['page_post' => $page_post])
        @endif
    @else
        @include('landlord.frontend.partials.dynamic-content')
    @endif

@endsection
