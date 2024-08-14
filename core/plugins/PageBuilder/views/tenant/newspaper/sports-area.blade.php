@php
    $left_blog = $data['left_blog'];
    $blogs = $data['blogs'];
@endphp


<section class="newspaper_sports_area  padding-top-50 padding-bottom-50">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row g-4">
            <div class="col-lg-9">
                <div class="newspaper_section__title border__bottom text-left title_flex">
                    <h4 class="title">{{$data['title']}}</h4>
                    <a href="{{$data['view_more_url']}}" class="viewMore_btn">{{__('View More')}} <i class="las la-arrow-right"></i></a>
                </div>
                <div class="row g-4 mt-1">
                    <div class="col-md-8">
                        <div class="newspaper_sports newspaper_sports_bg">
                            <div class="newspaper_sports__thumb">
                                <a href="{{ route('tenant.frontend.blog.single',$left_blog->slug) }}">
                                    {!! render_image_markup_by_attachment_id($left_blog->image) !!}
                                </a>
                            </div>
                            <div class="newspaper_sports__contents pt-4">
                                <div class="newspaper_sports__tag">
                                    <a href="javascript:void(0)" class="newspaper_sports__tag__item">
                                        <div class="newspaper_sports__tag__item__thumb">
                                            <img src="{{global_asset('assets/tenant/frontend/themes/img/newspaper/latest/newspaper_author1.jpg')}}" alt="authorImg">
                                        </div>
                                        <span class="newspaper_sports__tag__item__title">{{$left_blog->author}}</span>
                                    </a>
                                    <a href="javascript:void(0)" class="newspaper_sports__tag__item">
                                        <div class="newspaper_sports__tag__item__icon"><i class="las la-clock"></i></div>
                                        <span class="newspaper_sports__tag__item__title">{{ date('d M Y',strtotime($left_blog->created_at)) }}</span>
                                    </a>
                                    <a href="javascript:void(0)" class="newspaper_sports__tag__item">
                                        <div class="newspaper_sports__tag__item__icon"><i class="las la-comments"></i></div>
                                        <span class="newspaper_sports__tag__item__title">{{$left_blog->comments?->count()}}</span>
                                    </a>
                                </div>
                                <h4 class="newspaper_sports__title style-02 mt-3"><a href="javascript:void(0)">{{$left_blog->title}}</a></h4>
                                <p class="newspaper_sports__para style-02 mt-3">
                                {!! $left_blog->excerpt ?? $strip_tags(\Illuminate\Support\Str::words($left_blog->blog_content,35)) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row g-4">
                            @foreach($blogs as $data)

                                @php
                                    $comment_count = $data->comments?->count();
                                @endphp
                                <div class="newspaper_latest__item">
                                    <div class="newspaper_latest newspaper_latest_bg">
                                        <div class="newspaper_latest__thumb">
                                            <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">
                                                {!! render_image_markup_by_attachment_id($data->image) !!}
                                            </a>
                                        </div>
                                        <div class="newspaper_latest__contents pt-3">
                                            <h4 class="newspaper_latest__title">
                                                <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">{{$data->title}}</a>
                                            </h4>
                                            <div class="newspaper_latest__tag mt-2">
                                                <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}" class="newspaper_latest__tag__item">
                                                    <div class="newspaper_latest__tag__item__thumb">
                                                        <img src="{{global_asset('assets/tenant/frontend/themes/img/newspaper/latest/newspaper_author1.jpg')}}" alt="authorImg">
                                                    </div>
                                                    <span class="newspaper_latest__tag__item__title">{{$data->author}}</span>
                                                </a>
                                                <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}" class="newspaper_latest__tag__item">
                                                    <div class="newspaper_latest__tag__item__icon"><i class="las la-clock"></i></div>
                                                    <span class="newspaper_latest__tag__item__title">{{ date('d M Y',strtotime($data->created_at)) }}</span>
                                                </a>
                                                <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}" class="newspaper_latest__tag__item">
                                                    <div class="newspaper_latest__tag__item__icon"><i class="las la-comments"></i></div>
                                                    <span class="newspaper_latest__tag__item__title">{{$comment_count}}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="newspaper_sideWidget">
                    <div class="newspaper_section__title style-02 border__bottom text-left">
                        <h4 class="title">{{__('Stay Connected')}}</h4>
                    </div>
                    <div class="newspaper_sideWidget__inner mt-4">
                        {!! render_frontend_sidebar('newspaper_sidebar',['column'=>false]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
