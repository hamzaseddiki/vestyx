<section class="wedding_blog_area position-relative padding-top-50 padding-bottom-50" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="gradient_bg">
        <span></span>
        <span></span>
    </div>
    <div class="wedding_blog__shape">
        <img src="{{global_asset('assets/tenants/frontend/themes/img/wedding/blog/wedding_flower2.png')}}" alt="">
    </div>
    <div class="container">
        <div class="wedding_sectionTitle">
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
                    <div class="wedding_blog wedding_section_bg_2">
                        <div class="wedding_blog__thumb">
                            <a href="{{$url}}">
                                {!! render_image_markup_by_attachment_id($item->image) !!}
                            </a>
                        </div>
                        <div class="wedding_blog__contents">
                            <div class="wedding_blog__tag">
                                <a href="{{$url}}" class="wedding_blog__tag__item"><i class="fa-solid fa-calendar-days"></i> {{date('d M Y',strtotime($item->created_at))}}</a>
                                <a href="{{$category_route}}" class="wedding_blog__tag__item"><i class="fa-solid fa-tag"></i>{{$category}}</a>
                            </div>
                            <h3 class="wedding_blog__contents__title mt-3">
                                <a href="{{$url}}">{{$item->title}}</a>
                            </h3>
                            <p class="wedding_blog__contents__para mt-3">
                            {!! $item->excerpt ?? strip_tags(\Illuminate\Support\Str::words($item->blog_content,35)) !!}
                            </p>
                            <div class="btn-wrapper mt-4">
                                <a href="{{$url}}" class="wedding_blog__btn">{{$data['more_text']}}</a>
                            </div>
                        </div>
                    </div>
                </div>
          @endforeach

        </div>
        <div class="row">
            <div class="col-12">
                <div class="btn-wrapper center-text mt-4 mt-lg-5">
                    <a href="{{$data['bottom_text_url']}}" class="wedding_cmn_btn btn_gradient_main radius-30">{{$data['bottom_text']}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
