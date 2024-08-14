@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
    $all_data = $data['blogs'] ?? [];

@endphp

<section class="photography_blog_area " data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="photography_sectionTitle">
            <div class="section-tittle mb-0">
                {!! get_landlord_modified_title($data['title']) !!}
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mt-4">
                <div class="row">
                    @foreach($all_data as $item)
                        @php
                            $url = route('landlord.frontend.blog.single',$item->slug);
                            $category = $item->category?->title;
                            $category_route = route('landlord.frontend.blog.category',['id'=> $item->category_id, 'any' => \Illuminate\Support\Str::slug($category)]);
                        @endphp
                    <div class="slick-slider-item col-lg-4 col-6">
                        <div class="photography_blog {{!empty($data['default_bg']) ? 'sectionBg2' : ''}} " style="background-color: {{ $data['bg_color'] }}">
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
                                <p class="photography_blog__contents__para mt-3">{!! $item->excerpt ?? strip_tags(\Illuminate\Support\Str::words($item->blog_content,35)) !!}</p>
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

        <div class="row padding-top-50">
            <div class="col-lg-12">
                <div class="pagination">
                    {{ $all_data->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
