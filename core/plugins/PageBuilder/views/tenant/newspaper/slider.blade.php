<section class="newspaper_latest_area  padding-top-50 padding-bottom-50">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="newspaper_section__title border__bottom text-left title_flex">
            <h4 class="title">{{$data['title']}}</h4>
            <div class="append-latest"></div>
        </div>
        @php
            $lang_rtl_con = get_user_lang_direction() == 1 ? 'true' : 'false';
        @endphp
        <div class="row g-4 mt-1">
            <div class="col-lg-12">
                <div class="global-slick-init recent-slider nav-style-one slider-inner-margin" data-rtl="{{$lang_rtl_con}}" data-appendArrows=".append-latest" data-infinite="false" data-arrows="true" data-dots="false" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 576,"settings": {"slidesToShow": 1}}]'>

                    @foreach($data['blogs'] as $data)

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
</section>
