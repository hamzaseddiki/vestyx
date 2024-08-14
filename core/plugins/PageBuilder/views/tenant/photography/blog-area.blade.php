
<section class="photography_blog_area padding-top-50 padding-bottom-100" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="photography_blog__shapes">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/photography/photography_starShape.svg')}}" alt="starShape">
    </div>
    <div class="container">
        <div class="photography_sectionTitle">
                {!! get_modified_title_photography($data['title']) !!}
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mt-4">
                <div class="global-slick-init blog-slider dot-style-one slider-inner-margin" data-rtl="{{ get_slider_language_deriection() }}" data-infinite="false" data-arrows="false" data-dots="true" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 3}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}}]'>

                    @foreach($data['blogs'] ?? [] as $item)
                        @php
                            $url = route('tenant.frontend.blog.single',$item->slug);
                            $category = $item->category?->title;
                            $category_route = route('tenant.frontend.blog.category',['id'=> $item->category_id, 'any' => \Illuminate\Support\Str::slug($category)]);
                        @endphp
                    <div class="slick-slider-item">
                        <div class="photography_blog photography_section_bg_3">
                            <div class="photography_blog__thumb">
                                <a href="{{$url}}">
                                    {!! render_image_markup_by_attachment_id($item->image) !!}
                                </a>
                            </div>
                            <div class="photography_blog__contents">
                                <div class="photography_blog__tag">
                                    <a href="{{$url}}" class="photography_blog__tag__item"><i class="fa-regular fa-clock"></i>{{date('d M Y',strtotime($item->created_at))}}</a>
                                    <a href="{{$category_route}}" class="photography_blog__tag__item"><i class="las la-tag"></i>{{$category}}</a>
                                </div>
                                <h3 class="photography_blog__contents__title mt-3">
                                    <a href="{{$url}}">{{$item->title}}</a>
                                </h3>
                                <p class="photography_blog__contents__para mt-3">
                                {!! $item->excerpt ?? strip_tags(\Illuminate\Support\Str::words($item->blog_content,35)) !!}
                                </p>
                                <div class="btn-wrapper mt-3">
                                    <a href="{{$url}}" class="photography_blog__btn"> {{$data['more_text']}} <i class="fa-solid fa-arrow-right"></i> </a>
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
