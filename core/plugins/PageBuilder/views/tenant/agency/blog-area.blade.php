<section class="agency_blog_area padding-top-50 padding-bottom-100">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="agency_section__title text-left title_flex">
            {!! get_modified_title_agency_two($data['title']) !!}
            <div class="append-blog"></div>
        </div>
        @php
            $rtl_condition = get_user_lang_direction() == 1 ? 'true' : 'false';
        @endphp
        <div class="row mt-4">
            <div class="col-lg-12 mt-4">
                <div class="global-slick-init recent-slider nav-style-one slider-inner-margin" data-rtl="{{$rtl_condition}}" data-appendArrows=".append-blog" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 3}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}}]'>
                  @foreach($data['blogs'] ?? [] as $data)
                    <div class="slick-slider-item">
                        <div class="agency_blog">
                            <div class="agency_blog__thumb thumb_shape">
                                <a href="{{route('tenant.frontend.blog.single',$data->slug)}}">
                                    {!! render_image_markup_by_attachment_id($data->image) !!}
                                </a>
                            </div>
                            <div class="agency_blog__contents mt-3">
                                <h3 class="agency_blog__contents__title fw-600"> <a href="{{route('tenant.frontend.blog.single',$data->slug)}}">{{$data->title}}</a> </h3>
                                <p class="agency_blog__contents__para mt-3"> 
                                {!! $data->excerpt ?? strip_tags(\Illuminate\Support\Str::words($data->blog_content,35)) !!}
                                </p>
                                <div class="btn-wrapper mt-4">
                                    <a href="{{route('tenant.frontend.blog.single',$data->slug)}}" class="agency_service__btn"> {{__('Read Article')}} </a>
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
