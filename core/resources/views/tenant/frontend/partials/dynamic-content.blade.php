
<section class="dynamic-page-content-area" data-padding-top="100" data-padding-bottom="70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                @if( $page_post->visibility === 1)
                    @if(auth('web')->check())
                    <div class="dynamic-page-content-wrap">
                        {!! $page_post->getTranslation('page_content',$user_select_lang_slug) !!}
                    </div>
                    @else
                        <div class="alert alert-warning">
                            <p><a class="text-primary" href="{{route('login')}}">{{__('Login')}}</a> {{__(' to see this page')}} </p>
                        </div>
                    @endif
                 @else
                    <div class="dynamic-page-content-wrap">
                        {!! $page_post->getTranslation('page_content',$user_select_lang_slug) !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
