
<section class="consulting_blog_area padding-top-50 padding-bottom-100">
    <div class="body_border">
        <span class="one"></span>
        <span class="two"></span>
        <span class="three"></span>
        <span class="four"></span>
        <span class="five"></span>
        <span class="six"></span>
        <span class="seven"></span>
    </div>
    <div class="container">
        <div class="consulting_sectionTitle">
            <span class="subtitle">{{$data['title']}}</span>
            <h2 class="title">
                <span class="title__top">{{$data['subtitle']}}
                    <span class="title__top__shape">
                       <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_banner_title_shape.svg')}}" alt="">
                    </span>
                </span>
            </h2>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mt-4">
                <div class="global-slick-init blog-slider dot-style-one slider-inner-margin" data-rtl="{{get_slider_language_deriection()}}" data-infinite="false" data-arrows="false" data-dots="true" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 3}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}}]'>
                    @foreach($data['blogs'] ?? [] as $blog)
                    <div class="slick-slider-item">
                        <div class="consulting_blog consulting_section_bg_2">
                            <div class="consulting_blog__thumb">
                                <a href="{{route('tenant.frontend.blog.single',$blog->slug)}}">
                                    {!! render_image_markup_by_attachment_id($blog->image) !!}
                                </a>
                            </div>
                            <div class="consulting_blog__contents">
                                <div class="consulting_blog__tag">
                                    <a href="{{route('tenant.frontend.blog.single',$blog->slug)}}" class="consulting_blog__tag__item">{{ date('D m Y',strtotime($blog->created_at)) }}</a>
                                    <a href="{{route('tenant.frontend.blog.category',['id'=> $blog->category_id, 'any' => \Illuminate\Support\Str::slug($blog->title)])}}" class="consulting_blog__tag__item"><i class="fa-solid fa-tag"></i>
                                        {{ $blog->category?->title }}
                                    </a>
                                </div>
                                <h3 class="consulting_blog__contents__title mt-2"> <a href="{{route('tenant.frontend.blog.single',$blog->slug)}}">{{$blog->title}}</a> </h3>
                                <p class="consulting_blog__contents__para mt-3">
                                    {!! $blog->excerpt ?? strip_tags(\Illuminate\Support\Str::words($blog->blog_content,35)) !!}
                                </p>
                                <div class="btn-wrapper mt-3">
                                    <a href="{{route('tenant.frontend.blog.single',$blog->slug)}}" class="consulting_blog__btn"> {{__('Explore more')}} <i class="fa-solid fa-arrow-right"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
