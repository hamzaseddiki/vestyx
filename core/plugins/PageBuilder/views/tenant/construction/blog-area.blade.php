<section class="construction_blog_area padding-top-50 padding-bottom-100">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="construction_sectionTitle__two text-left title_flex">
            <h2 class="title">{{$data['title']}}</h2>
            <div class="append_blog"></div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mt-4">
                <div class="global-slick-init blog-slider dot-style-one slider-inner-margin" data-rtl="{{get_slider_language_deriection()}}" data-appendArrows=".append_blog" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="false" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 3}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}}]'>

                    @foreach($data['blogs'] ?? [] as $blog)
                        <div class="slick-slider-item">
                            <div class="construction_blog construction_section_bg_2">
                                <div class="construction_blog__thumb">
                                    <a href="{{route('tenant.frontend.blog.single',$blog->slug)}}">
                                       {!! render_image_markup_by_attachment_id($blog->image) !!}
                                    </a>
                                </div>
                                <div class="construction_blog__contents">
                                    <div class="construction_blog__tag">
                                        <a href="{{route('tenant.frontend.blog.single',$blog->slug)}}" class="construction_blog__tag__item"><i class="fa-solid fa-calendar-days"></i> {{ date('D m Y',strtotime($blog->created_at)) }}</a>
                                        <a href="{{route('tenant.frontend.blog.category',['id'=> $blog->category_id, 'any' => \Illuminate\Support\Str::slug($blog->title)])}}" class="construction_blog__tag__item"><i class="fa-solid fa-tag"></i>
                                            {{ $blog->category?->title }}
                                        </a>
                                    </div>
                                    <h3 class="construction_blog__contents__title mt-3"> <a href="{{route('tenant.frontend.blog.single',$blog->slug)}}">{{$blog->title}}</a> </h3>
                                    <p class="construction_blog__contents__para mt-3">
                                    {!! $blog->excerpt ?? strip_tags(\Illuminate\Support\Str::words($blog->blog_content,35)) !!}
                                      
                                    </p>
                                    <div class="btn-wrapper mt-3">
                                        <a href="{{route('tenant.frontend.blog.single',$blog->slug)}}" class="construction_blog__btn"> {{__('Read more')}} <i class="fa-solid fa-arrow-right"></i> </a>
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
