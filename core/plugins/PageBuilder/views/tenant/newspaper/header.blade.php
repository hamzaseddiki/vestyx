@php
    $left_blog = $data['left_blog'];
    $blogs = $data['blogs'];
@endphp

<div class="newspaper_banner newspaper_banner__padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">

        <div class="row g-4">
            <div class="col-xl-6">
                <div class="newspaper_banner__left">
                    <div class="newspaper_banner__thumb">
                        <a href="{{ route('tenant.frontend.blog.single',$left_blog->slug) }}">
                          {!! render_image_markup_by_attachment_id($left_blog->image) !!}
                        </a>
                    </div>
                    <div class="newspaper_banner__content mt-4">
                        <div class="newspaper_banner__content__tag">
                            <a href="{{ route('tenant.frontend.blog.category',['id'=> $left_blog->category_id, 'any' => \Illuminate\Support\Str::slug($left_blog->category?->title)]) }}" class="newspaper_banner__content__tag__item"><i class="las la-tags"></i>{{$left_blog->category?->title}}</a>
                            <a href="{{ route('tenant.frontend.blog.single',$left_blog->slug) }}" class="newspaper_banner__content__tag__item"><i class="las la-clock"></i> {{ date('d M Y',strtotime($left_blog->created_at)) }}</a>
                        </div>
                        <h2 class="newspaper_banner__content__title mt-3"><a href="{{ route('tenant.frontend.blog.single',$left_blog->slug) }}">{{$left_blog->title}}</a></h2>
                        <p class="newspaper_banner__content__para mt-3">{!! \Illuminate\Support\Str::words($left_blog->blog_content,85) !!}</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="newspaper_banner__right">
                    @foreach($blogs as $data)
                        <div class="newspaper_banner__news">
                            <div class="newspaper_banner__news__flex">
                                <div class="newspaper_banner__news__thumb">
                                    <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">
                                        {!! render_image_markup_by_attachment_id($data->image) !!}
                                    </a>
                                </div>
                                <div class="newspaper_banner__news__contents">
                                    <div class="newspaper_banner__news__date">
                                        <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}" class="newspaper_banner__news__date__item"><i class="las la-clock"></i> <span>{{ date('d M Y',strtotime($data->created_at)) }}</span></a>
                                    </div>
                                    <h5 class="newspaper_banner__news__title mt-1"><a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">{{$data->title}}</a></h5>
                                    <p class="newspaper_banner__news__para mt-2">
                                    {!! $data->excerpt ?? $strip_tags(\Illuminate\Support\Str::words($data->blog_content,35)) !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
