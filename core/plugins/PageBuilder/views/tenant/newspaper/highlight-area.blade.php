<section class="newspaper_highlight_area  padding-top-50 padding-bottom-50">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row g-4">
            <div class="col-lg-9">
                <div class="newspaper_section__title border__bottom text-left title_flex">
                    <h4 class="title">{{$data['title']}}</h4>
                    <a href="{{$data['view_more_url']}}" class="viewMore_btn">{{__('View More')}} <i class="las la-arrow-right"></i></a>
                </div>
                <div class="row g-4 mt-1">
                    @foreach($data['blogs'] as $data)
                        @php
                            $comment_count = $data->comments?->count();
                        @endphp
                        <div class="col-xl-4 col-lg-6 col-sm-6">
                            <div class="newspaper_highlight newspaper_highlight_bg">
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
            <div class="col-lg-3">
                <div class="newspaper_sideWidget">
                    {!! render_frontend_sidebar('newspaper_sidebar_two',['column'=>false]) !!}
                </div>
            </div>
        </div>
    </div>
</section>
