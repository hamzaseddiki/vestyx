@php
    $left_blog = $data['left_blog'];
    $all_categories = $data['all_categories'];
    $blogs = $data['blogs'];

    $recent_blogs = $data['recent_blogs'];
    $trending = $data['trending'];
    $most_viewed = $data['most_viewed'];
@endphp


<section class="newspaper_popular_area padding-top-50 padding-bottom-50">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row g-4">
            <div class="col-xl-4">
                <div class="newspaper_popular__right">
                    <div class="newspaper_section__title border__bottom text-left">
                        <h4 class="title">{{$data['title']}}</h4>
                    </div>
                    <div class="newspaper_popular__bottom mt-4">
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
                                <p class="newspaper_banner__content__para mt-3">
                                {!! $left_blog->excerpt ?? $strip_tags(\Illuminate\Support\Str::words($left_blog->blog_content,40)) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="newspaper_popular__isotope border__bottom">
                            <ul class="newspaper_popular__isotope__list">
                                <li class=" news_all_cat active">
                                    <a href="javascript:void(0)">{{__('All')}}</a>
                                </li>
                                @foreach($all_categories as $category)
                                     <li class="newspaper_category_item" data-category_id="{{$category->id}}"><a href="#!">{{$category->title}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="newspaper_popular__right mt-4 middle_news_container">
                            @foreach($blogs as $data)
                                <div class="newspaper_banner__news">
                                    <div class="newspaper_banner__news__flex">
                                        <div class="newspaper_popular__news__thumb">
                                            <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">
                                                {!! render_image_markup_by_attachment_id($data->image) !!}
                                            </a>
                                        </div>
                                        <div class="newspaper_banner__news__contents">
                                            <div class="newspaper_banner__news__date">
                                                <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}" class="newspaper_banner__news__date__item"><i class="las la-clock"></i> <span>{{ date('d M Y',strtotime($data->created_at)) }}</span></a>
                                            </div>
                                            <h5 class="newspaper_banner__news__title mt-1"><a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">{{$data->title}}</a></h5>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="newspaper_popular__tab">
                            <ul class="newspaper_popular__tab__list tabs">
                                <li data-tab="recent" class="active">{{__('Recent')}}</li>
                                <li data-tab="trending">{{__('Trending')}}</li>
                                <li data-tab="mostView">{{__('Most View')}}</li>
                            </ul>
                        </div>
                        <div class="newspaper_popular__tabContent mt-4">
                            <div class="tab-content-item active" id="recent">
                                @foreach($recent_blogs as $data)
                                    <div class="newspaper_popular__news">
                                        <div class="newspaper_popular__news__flex">
                                            <div class="newspaper_popular__news__thumb">
                                                <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">
                                                    {!! render_image_markup_by_attachment_id($data->image) !!}
                                                </a>
                                            </div>
                                            <div class="newspaper_popular__news__contents">
                                                <div class="newspaper_popular__news__date">
                                                    <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}" class="newspaper_popular__news__date__item"><i class="las la-clock"></i> <span>{{ date('d M Y',strtotime($data->created_at)) }}</span></a>
                                                </div>
                                                <h5 class="newspaper_popular__news__title mt-1">
                                                    <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">{{$data->title}}</a>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                 @endforeach

                            </div>
                            <div class="tab-content-item" id="trending">

                                @foreach($trending as $data)
                                    <div class="newspaper_popular__news">
                                        <div class="newspaper_popular__news__flex">
                                            <div class="newspaper_popular__news__thumb">
                                                <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">
                                                    {!! render_image_markup_by_attachment_id($data->image) !!}
                                                </a>
                                            </div>
                                            <div class="newspaper_popular__news__contents">
                                                <div class="newspaper_popular__news__date">
                                                    <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}" class="newspaper_popular__news__date__item"><i class="las la-clock"></i> <span>{{ date('d M Y',strtotime($data->created_at)) }}</span></a>
                                                </div>
                                                <h5 class="newspaper_popular__news__title mt-1">
                                                    <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">{{$data->title}}</a>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <div class="tab-content-item" id="mostView">
                                @foreach($most_viewed as $data)
                                    <div class="newspaper_popular__news">
                                        <div class="newspaper_popular__news__flex">
                                            <div class="newspaper_popular__news__thumb">
                                                <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">
                                                    {!! render_image_markup_by_attachment_id($data->image) !!}
                                                </a>
                                            </div>
                                            <div class="newspaper_popular__news__contents">
                                                <div class="newspaper_popular__news__date">
                                                    <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}" class="newspaper_popular__news__date__item"><i class="las la-clock"></i> <span>{{ date('d M Y',strtotime($data->created_at)) }}</span></a>
                                                </div>
                                                <h5 class="newspaper_popular__news__title mt-1">
                                                    <a href="{{ route('tenant.frontend.blog.single',$data->slug) }}">{{$data->title}}</a>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



@section('scripts')
    <script>
        $(function (){

            $(document).on('click', '.newspaper_category_item', function (e){
                e.preventDefault();

                $(this).siblings().siblings().removeClass('active');
                $(this).addClass('active');

                let el = $(this);
                let category_id = el.data('category_id');

                $.ajax({
                    type: 'GET',
                    url: "{{route('tenant.frontend.home.news.by.category.ajax')}}",
                    data: {
                        category_id : category_id,
                    },
                    beforeSend: function (){
                        CustomLoader.start()
                    },
                    success: function (data){
                      $('.middle_news_container').html(data);
                        CustomLoader.end();
                    },
                    error: function (data){
                        console.log(data)
                    }
                });

            });

            $(document).on('click', '.news_all_cat', function (e){
                e.preventDefault();
                $(this).siblings().removeClass('active');
                $(this).addClass('active');

                 CustomLoader.start()
                $('.middle_news_container').load(location.href + ' .middle_news_container');

                setTimeout(() => {
                    CustomLoader.end();
                }, 300)


            });


        });
    </script>
@endsection

