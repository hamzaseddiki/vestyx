<section class="barberShop_blog_area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="barberShop_blog__shapes">
        {!! render_image_markup_by_attachment_id($data['bottom_short_image']) !!}
    </div>
    <div class="container">
        <div class="barberShop_sectionTitle">
            {!! get_modified_title_barber_two($data['title']) !!}
        </div>

        @php
            $button_text = $data['button_text'];
        @endphp
        <div class="row g-4 mt-4">
            @foreach($data['blogs'] ?? [] as $data)
                <div class="col-lg-4 col-md-6">
                    <div class="barberShop_blog">
                        <div class="barberShop_blog__tag">
                            <a href="{{route('tenant.frontend.blog.category',['id'=> $data->category_id, 'any' => \Illuminate\Support\Str::slug($data->title)])}}" class="barberShop_blog__tag__item">{{ $data->category?->title }}</a>
                        </div>
                        <div class="barberShop_blog__thumb">
                            <a href="{{route('tenant.frontend.blog.single',$data->slug)}}">
                                {!! render_image_markup_by_attachment_id($data->image) !!}
                            </a>
                        </div>
                        <div class="barberShop_blog__contents">
                            <div class="barberShop_blog__contents__tag">
                                <a href="{{route('tenant.frontend.blog.single',$data->slug)}}" class="barberShop_blog__contents__tag__item">By Chrish Jordan</a>
                                <a href="{{route('tenant.frontend.blog.single',$data->slug)}}" class="barberShop_blog__contents__tag__item"><i class="fa-regular fa-calendar-days"></i> {{$data->created_at?->format('d M, Y')}}</a>
                                <a href="{{route('tenant.frontend.blog.single',$data->slug)}}" class="barberShop_blog__contents__tag__item"><i class="fa-regular fa-eye"></i> {{$data->views}}</a>
                            </div>
                            <h3 class="barberShop_blog__contents__title mt-3"> <a href="{{route('tenant.frontend.blog.single',$data->slug)}}">{{$data->title}} </a> </h3>
                            <p class="barberShop_blog__contents__para mt-3"> 
                                {!! $data->excerpt ?? strip_tags(\Illuminate\Support\Str::words($data->blog_content,35)) !!}
                            </p>
                            <div class="btn-wrapper mt-3">
                                <a href="{{route('tenant.frontend.blog.single',$data->slug)}}" class="barberShop_cmn_btn btn_outline_1 btn_small color_one">{{$button_text}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
