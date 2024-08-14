@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{ $category_name}}
@endsection
@section('page-title')
    {{__('Category: ').$category_name}}
@endsection
@section('content')

    <section class="blog-content-area" data-padding-top="110" data-padding-bottom="110">
        <div class="container">
            <div class="row">
                <div class="col-xxl-4 col-xl-4 col-lg-5 col-md-6">
                    <x-blog::frontend.sidebar-data/>
                </div>
                <div class="col-lg-8">
                    <div class="row">

                        @if(count($all_blogs) < 1)
                            <div class="col-lg-12">
                                <div class="alert alert-warning">
                                    {{__('No Post Available In ') .' : ' .$category_name.__(' Category')}}
                                </div>
                            </div>
                        @endif

                        @php
                            $user_lang = get_user_lang();
                        @endphp

                        @foreach($all_blogs as $data)
                                @php
                                    $title = $data->category?->getTranslation('title',get_user_lang());
                                    $url = route('tenant.frontend.blog.category', ['id' => $data->category?->id,'any' => Str::slug($title)]);
                                @endphp
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <figure class="singleBlog-global mb-24">
                                    <div class="blog-img overlay1">
                                        <a href="{{route('tenant.frontend.blog.single',$data->slug)}}">
                                            {!! render_image_markup_by_attachment_id($data['image']) !!}
                                        </a>
                                        <div class="img-text">
                                            <a href="{{$url}}">
                                                <span class="content">{{$data->category?->getTranslation('title',get_user_lang())}}</span>
                                            </a>
                                        </div>
                                    </div>
                                    <figcaption class="blogCaption">
                                        <ul class="cartTop">
                                            <li class="listItmes"><i class="fa-solid fa-calculator icon"></i> {{$data->created_at?->format('d M, Y')}}</li>
                                            <li class="listItmes"><i class="fa-solid fa-eye icon"></i> {{$data->views}}</li>
                                            <li class="listItmes"><i class="fa-solid fa-comment icon"></i> {{ $data->comments?->count() ??  0}} </li>
                                        </ul>
                                        <h3><a href="{{route('tenant.frontend.blog.single',$data->slug)}}" class="tittle">{{$data->getTranslation('title',get_user_lang())}}</a></h3>
                                        <p class="pera">{!! \Illuminate\Support\Str::words(purify_html($data->getTranslation('blog_content',get_user_lang())),25) !!}</p>
                                        <!-- Blog Footer -->
                                        <div class="blogFooter">
                                            <div class="blogPostUser mb-20">
                                                {!! render_image_markup_by_attachment_id(get_blog_created_user_image($data->admin_id)) !!}
                                                <h3><a href="#" class="tittle" >{{$data->admin?->name}}</a></h3>
                                            </div>
                                            <div class="contacts mb-20">
                                                <ul class="listing">
                                                    <li class="listItem"><i class="fa-solid fa-share-nodes icon"></i></li>
                                                    <li class="listItem"><i class="fa-solid fa-bookmark icon"></i></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-lg-12">
                            {!! $all_blogs->links() !!}
                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection
