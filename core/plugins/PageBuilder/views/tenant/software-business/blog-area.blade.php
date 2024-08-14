<section class="softwareFirm_blog_area padding-top-100 padding-bottom-50" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="softwareFirm_sectionTitle">
            <h2 class="title">{{$data['title']}}</h2>
        </div>
        <div class="row g-4 mt-4">
            @foreach($data['blogs'] ?? [] as $item)
                @php
                    $url = route('tenant.frontend.blog.single',$item->slug);
                    $category = $item->category?->title;
                    $category_route = route('tenant.frontend.blog.category',['id'=> $item->category_id, 'any' => \Illuminate\Support\Str::slug($category)]);
                @endphp
            <div class="col-lg-4 col-md-6">
                <div class="softwareFirm_blog softwareFirm_section_bg_2">
                    <div class="softwareFirm_blog__thumb">
                        <a href="{{$url}}">
                            {!! render_image_markup_by_attachment_id($item->image) !!}
                        </a>
                    </div>
                    <div class="softwareFirm_blog__contents">
                        <div class="softwareFirm_blog__tag">
                            <a href="{{$url}}" class="softwareFirm_blog__tag__item"><i class="fa-solid fa-calendar-days"></i>{{date('d M Y',strtotime($item->created_at))}}</a>
                            <a href="{{$category_route}}" class="softwareFirm_blog__tag__item"><i class="las la-tag"></i>{{$category}}</a>
                        </div>
                        <h3 class="softwareFirm_blog__contents__title mt-3"> <a href="{{$url}}">{{$item->title}}</a> </h3>
                        <p class="softwareFirm_blog__contents__para mt-3">
                            {!! $item->excerpt ?? strip_tags(\Illuminate\Support\Str::words($item->blog_content,35)) !!}
                        </p>
                        <div class="btn-wrapper mt-3">
                            <a href="{{$url}}" class="softwareFirm_blog__btn"> {{$data['more_text']}} <i class="fa-solid fa-arrow-right"></i> </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
