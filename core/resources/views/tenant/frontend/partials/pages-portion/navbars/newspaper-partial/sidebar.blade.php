<div class="sidebars-wrappers newspaper_sidebar">
    <div class="sidebars-close"> <i class="las la-times"></i> </div>
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <a href="{{url('/')}}">
            {!! render_image_markup_by_attachment_id(get_static_option('site_logo'),'logo') !!}
        </div>
        <div class="contents-wrapper">
            <h4 class="connets-title"> {{__('Connect with us')}} </h4>
            <div class="updated-socials">
                <ul class="common-socials">
                    <li>
                        <a class="facebook" href="{{get_static_option('topbar_facebook_url')}}"> <i class="lab la-facebook-f"></i> </a>
                    </li>
                    <li>
                        <a class="twitter" href="{{get_static_option('topbar_twitter_url')}}"> <i class="lab la-twitter"></i> </a>
                    </li>
                    <li>
                        <a class="instagram" href="{{get_static_option('topbar_instagram_url')}}"> <i class="lab la-instagram"></i> </a>
                    </li>
                    <li>
                        <a class="linkedin" href="{{get_static_option('topbar_linkedin_url')}}"> <i class="lab la-linkedin-in"></i> </a>
                    </li>
                    <li>
                        <a class="youtube" href="{{get_static_option('topbar_youtube_url')}}"> <i class="lab la-youtube"></i> </a>
                    </li>
                </ul>
            </div>
            <div class="sidebar-updated-content">
                <h4 class="connets-title"> {{__('Categories')}} </h4>
                <div class="categories-contents-inner mt-4">
                    @php
                        $categories = \Modules\Blog\Entities\BlogCategory::where('status',1)->get();
                    @endphp

                    <div class="categories-lists">
                        @foreach($categories as $data)
                            @php
                                $count_category = \Modules\Blog\Entities\Blog::where('category_id',$data->id)->count();
                            @endphp
                            <div class="single-list">
                                <span class="follow-para"> <a href="{{ route('tenant.frontend.blog.category',['id'=> $data->id, 'any' => \Illuminate\Support\Str::slug($data->title)]) }}"> {{$data->title}} </a> </span>
                                <span class="followers"> {{$count_category}} </span>
                            </div>
                        @endforeach
                    </div>
                </div>
{{--                <div class="tag-new-contents">--}}
{{--                    <h4 class="connets-title"> Tags </h4>--}}
{{--                    <div class="tag-list">--}}
{{--                        <a class="list" href="#0"> Travel </a>--}}
{{--                        <a class="list" href="#0"> Fashion </a>--}}
{{--                        <a class="list" href="#0"> Global </a>--}}
{{--                        <a class="list" href="#0"> Business </a>--}}
{{--                        <a class="list" href="#0"> Sports </a>--}}
{{--                        <a class="list" href="#0"> Covid-19 </a>--}}
{{--                        <a class="list" href="#0"> Technology </a>--}}
{{--                        <a class="list" href="#0"> Marketing </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
</div>
