@extends('tenant.frontend.frontend-page-master')

@php
    $post_img = null;
    $user_lang = get_user_lang();

            $site_title_con = !empty($knowledgebase->metainfo?->getTranslation('title',$user_lang))
     ? $knowledgebase->metainfo?->getTranslation('title',$user_lang)
      : $knowledgebase->getTranslation('title',$user_lang);
@endphp

@section('page-title')
    {{ $site_title_con }}
@endsection

@section('title')
    {{ $site_title_con }}
@endsection

@section('meta-data')
    {!!  render_page_meta_data($knowledgebase) !!}
@endsection

@section('content')

    <div class="articleDetails section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-5">
                    <div class="simplePresentCart pb-0">
                        <div class="searchBox-wrapper searchBox-wrapper-sidebar">
                           <x-error-msg/>
                            <form action="{{route('tenant.frontend.knowledgebase.search.page')}}" class="search-box search-box2" method="get">
                                <div class="input-form">
                                    <input type="text" name="search" class=" keyup-input" placeholder="Search">
                                    <button type="submit" class="icon">
                                        <i class="las la-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="singleArticles singleArticles2 mb-24">
                            <h4 class="articlesTittle">{{__('Topic')}}</h4>
                            <ul class="listing">
                                @foreach($all_categories as $cat)
                                    <li class="listItem">
                                        {!! render_image_markup_by_attachment_id($cat->image,'icon') !!}
                                        <a href="{{ route('tenant.frontend.knowledgebase.category',['id' => $cat->id, 'any' => \Illuminate\Support\Str::slug($cat->title)]) }}">
                                            <blockquote class="articlesTag">{{ $cat->getTranslation('title',get_user_lang()) }} </blockquote>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>

                            <h4 class="articlesTittle">{{__('Popular')}}</h4>
                            <ul class="listing">
                                @foreach($all_popular_articles as $article)
                                    <li class="listItem">
                                        {!! render_image_markup_by_attachment_id($article->image,'icon') !!}
                                        <a href="{{ route('tenant.frontend.knowledgebase.single',$article->slug) }}">
                                            <blockquote class="articlesTag">{{ $article->getTranslation('title',get_user_lang()) }} </blockquote>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <h4 class="articlesTittle">{{__('Recent')}}</h4>
                            <ul class="listing">
                                @foreach($all_recent_articles as $article)
                                    <li class="listItem">
                                        {!! render_image_markup_by_attachment_id($article->image,'icon') !!}
                                        <a href="{{ route('tenant.frontend.knowledgebase.single',$article->slug) }}">
                                            <blockquote class="articlesTag">{{ $article->getTranslation('title',get_user_lang()) }} </blockquote>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7">
                    <!-- Single -->
                    <div class="capDetails mb-40">
                        <div class="details-img imgEffect">
                            {!! render_image_markup_by_attachment_id($knowledgebase->image) !!}
                        </div>
                        <div class="caption">
                            <ul class="cartTop">
                                <li class="listItmes"><i class="lar la-calendar icon"></i>{{ $knowledgebase->created_at?->diffForHumans() }}</li>
                                <a href="{{ route('tenant.frontend.knowledgebase.category',['id' => $knowledgebase->category_id, 'any' => \Illuminate\Support\Str::slug($knowledgebase->category?->title)]) }}">
                                    <li class="listItmes"><i class="las la-tag icon"></i> {{$knowledgebase->category?->getTranslation('title',get_user_lang())}}</li>
                                </a>

                                <li class="listItmes"><i class="las la-eye icon"></i> {{$knowledgebase->views ?? 0 }}</li>
                            </ul>
                        </div>
                    </div>

                    <div class="description mb-40">
                        <h5 class="title">{{$knowledgebase->getTranslation('title',get_user_lang())}}</h5>
                    </div>

                    <div class="description mb-50">
                        {!! $knowledgebase->getTranslation('description',get_user_lang()) !!}

                        @php
                            $files_decoded = json_decode($knowledgebase->files) ?? [];
                        @endphp

                        @if(count($files_decoded) > 0)
                            <div class="files mt-50">
                                <span><strong>{{__('Download Files')}} <i class="las la-arrow-circle-down"></i></strong></span>
                                <ol>
                                    @foreach($files_decoded as $file)
                                        @php
                                            $url_condition = file_exists('assets/uploads/article-files/'.$file)  ? url('assets/uploads/article-files/'.$file) : '';
                                        @endphp
                                        <li><a class="text-primary" href="{{$url_condition}}" target="_blank">{{ $file }}</a></li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif


                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')

@endsection
