
<section class="blogArea top-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row ">
            <div class="col-xl-8 col-lg-10 col-md-10 col-sm-10">
                <div class="section-tittle mb-40">
                    <h2 class="tittle">{{$data['title']}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="global-slick-init slider-inner-margin arrowStyleThree" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                 data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 991,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>

                @foreach($data['blogs'] as $item)
                    <div class="singleBlog mb-24">
                        <div class="blog-img overlay1">
                            <a href="{{route('tenant.frontend.blog.single',$item->slug)}}">
                                {!! render_image_markup_by_attachment_id($item->image) !!}
                            </a>
                        </div>
                        <div class="blogCaption">
                            <ul class="cartTop d-inline-flex">
                                <li class="listItmes">
                                    <i class="fa-solid fa-calculator icon"></i>
                                    {{date('d M Y',strtotime($item->created_at))}}
                                </li>

                                <li>
                                    <a href="{{route('tenant.frontend.blog.category',['id'=> $item->category_id, 'any' => \Illuminate\Support\Str::slug($item->title)])}}" class="consulting_blog__tag__item"><i class="fa-solid fa-tag"></i>
                                        {{ $item->category?->title }}
                                    </a>
                                </li>
                            </ul>
                            <h3><a href="{{route('tenant.frontend.blog.single',$item->slug)}}" class="tittle">{{ $item->getTranslation('title',get_user_lang()) }}</a></h3>
                            <p class="pera">
                            
                                {!! $item->getTranslation('excerpt',get_user_lang()) ?? \Illuminate\Support\Str::words(purify_html($item->getTranslation('blog_content',get_user_lang())),25) !!}
                            </p>
                            <!-- Blog Footer -->
                            <div class="blogFooter">
                                <div class="blogPostUser mb-20">
                                    {!! render_image_markup_by_attachment_id(get_blog_created_user_image($item->admin_id,'','grid')) !!}
                                    <h3><a href="{{route('tenant.frontend.blog.single',$item->slug)}}" class="tittle" >{{$item->admin?->name}}</a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
