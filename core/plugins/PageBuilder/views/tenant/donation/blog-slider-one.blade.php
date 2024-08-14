@php
    $user_lang = get_user_lang();
@endphp
<section class="blogArea section-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
            <div class="col-xl-8 col-lg-10 col-md-10 col-sm-10">
                <div class="section-tittle mb-40">
                    {!! get_modified_title_tenant($data['title']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="global-slick-init slider-inner-margin sliderArrow" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left-long"></i></div>'
                 data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right-long"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 991,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>

                @foreach($data['blogs'] as $item)
                    <div class="singleBlog">
                        <div class="blog-img overlay1">
                            <a href="{{route('tenant.frontend.blog.single',$item->slug)}}">
                                {!! render_image_markup_by_attachment_id($item->image) !!}
                            </a>
                            <div class="img-text">
                                <span class="content">{{$item->category?->getTranslation('title',$user_lang)}}</span>
                            </div>
                        </div>
                        <div class="blogCaption">
                            <ul class="cartTop">
                                <li class="listItmes"><i class="fa-solid fa-calculator icon"></i>{{ $item->created_at?->format('d M Y') }}</li>
                                <li class="listItmes">
                                    <a href="{{route('tenant.frontend.blog.category',['id'=> $item->category_id, 'any' => \Illuminate\Support\Str::slug($item->title)])}}" class=""><i class="fa-solid fa-tag icon"></i>
                                        {{ $item->category?->title }}
                                    </a>
                                </li>
                                <li class="listItmes"><i class="fa-solid fa-eye icon"></i> {{$item->views}}</li>
                                <li class="listItmes"><i class="fa-solid fa-comment icon"></i> {{$item->comment?->count()}} {{__('Comment')}}</li>
                            </ul>
                            <h3><a href="{{route('tenant.frontend.blog.single',$item->slug)}}" class="tittle">{{ $item->getTranslation('title',$user_lang) }}</a></h3>
                            <p class="pera">
                            
                                {!! $item->getTranslation('excerpt',$user_lang) ?? \Illuminate\Support\Str::words(purify_html_raw($item->getTranslation('blog_content',$user_lang)),20)  !!}
                            </p>
                            <!-- Blog Footer -->
                            <div class="blogFooter">
                                <div class="blogPostUser mb-20">
                                    {!! render_image_markup_by_attachment_id(get_blog_created_user_image($item->admin_id)) !!}
                                    <h3><a href="{{route('tenant.frontend.blog.single',$item->slug)}}" class="tittle" >{{$item->admin?->name}}</a></h3>
                                </div>
                                <div class="contacts mb-20">
                                    <ul class="listing">
                                        <li class="listItem"><i class="fa-solid fa-share-nodes icon"></i></li>
                                        <li class="listItem"><i class="fa-solid fa-bookmark icon"></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
