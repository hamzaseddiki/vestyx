
<section class="blogArea-global section-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
                @php
                    $all_blogs = $data['blogs'];
                @endphp
                @foreach($data['blogs'] as $data)
                    @php
                        $title = $data->category?->getTranslation('title',get_user_lang());
                        $url = route('tenant.frontend.blog.category', ['id' => $data->category?->id,'any' => Str::slug($title)]);
                    @endphp
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <figure class="singleBlog-global mb-24">
                            <div class="blog-img overlay1">
                                <a href="{{route('tenant.frontend.blog.single',$data->slug)}}">
                                    {!! render_image_markup_by_attachment_id($data['image']) !!}
                                </a>
                                <div class="img-text">
                                    <a href="{{$url}}">
                                        <span class="content">{{$data->category?->getTranslation('title',get_user_lang())}}</span>
                                    </a>
                                </div>
                            </div>
                            <figcaption class="blogCaption">
                                <ul class="cartTop">
                                    <li class="listItmes"><i class="fa-solid fa-calculator icon"></i> {{$data->created_at?->format('d M, Y')}}</li>
                                    <li class="listItmes">
                                        <a href="{{route('tenant.frontend.blog.category',['id'=> $data->category_id, 'any' => \Illuminate\Support\Str::slug($data->title)])}}">
                                            <i class="fa-solid fa-tag icon"></i> {{ $data->category?->title }}
                                        </a>
                                    </li>
                                    <li class="listItmes"><i class="fa-solid fa-eye icon"></i> {{$data->views}}</li>
                                    <li class="listItmes"><i class="fa-solid fa-comment icon"></i> {{ $data->comments?->count() ??  0}} </li>
                                </ul>
                                <h3><a href="{{route('tenant.frontend.blog.single',$data->slug)}}" class="tittle">{{$data->getTranslation('title',get_user_lang())}}</a></h3>
                                <p class="pera">

                                    {!! $data->getTranslation("excerpt",get_user_lang()) ?? \Illuminate\Support\Str::words(purify_html($data->getTranslation('blog_content',get_user_lang())),25) !!}
                                </p>
                                <!-- Blog Footer -->
                                <div class="blogFooter">
                                    <div class="blogPostUser mb-20">
                                       {!! render_image_markup_by_attachment_id(get_blog_created_user_image($data->admin_id)) !!}
                                        <h3><a href="#" class="tittle" >{{$data->admin?->name}}</a></h3>
                                    </div>
                                    <div class="contacts mb-20">
                                        {!! single_blog_post_share(route('tenant.frontend.blog.single',$data->slug),$data->getTranslation('title',get_user_lang()),$data->image) !!}
                                    </div>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                @endforeach

    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pagination mt-40 mb-30">
                        {!! $all_blogs->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
