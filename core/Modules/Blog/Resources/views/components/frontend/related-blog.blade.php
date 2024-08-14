
@php
    $margin_for_not_login_user = !auth()->guard('web')->check() ? 'mt-50' : '';
@endphp

<div class="row {{$margin_for_not_login_user}}">
    <div class="col-xl-12">
        <div class="section-tittle mb-30">
            <h2 class="tittle">{{__('Related Blog')}}</h2>
        </div>
    </div>

    @foreach($allRelatedBlogs as $data)
        <div class="col-lg-4 col-md-6 ">
            <figure class="singleBlog-global mb-24">
                <div class="blog-img overlay1">
                    <a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}">
                        {!! render_image_markup_by_attachment_id($data->image) !!}
                    </a>
                    <div class="img-text">
                        <span class="content">{{$data->category?->getTranslation('title',get_user_lang())}}</span>
                    </div>
                </div>
                <figcaption class="blogCaption">
                    <ul class="cartTop">
                        <li class="listItmes"><i class="fa-solid fa-calculator icon"></i>{{$data->created_at?->format('d M Y')}}</li>
                        <li class="listItmes"><i class="fa-solid fa-eye icon"></i> {{$data->views}}</li>
                        <li class="listItmes"><i class="fa-solid fa-comment icon"></i> {{$data->comments?->count()}} {{__('Comment')}}</li>
                    </ul>
                    <h3><a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}" class="tittle">{{ $data->getTranslation('title',get_user_lang()) }}</a></h3>
                    <p class="pera mb-4">{!! \Illuminate\Support\Str::words(strip_tags($data->getTranslation('blog_content',get_user_lang())),40) !!}</p>
                    <br>
                </figcaption>
            </figure>
        </div>
    @endforeach


</div>
