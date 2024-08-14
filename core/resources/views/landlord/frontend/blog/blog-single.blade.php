@extends('landlord.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('title')
    {{ !empty($blog_post->metainfo?->getTranslation('title',get_user_lang())) ?  $blog_post->metainfo?->getTranslation('title',get_user_lang()) : $blog_post->getTranslation('title',get_user_lang()) }}
@endsection

@section('page-title')
    {{ $blog_post->getTranslation('title',$user_lang) }}
@endsection

@section('meta-data')
    {!!  render_page_meta_data($blog_post) !!}
@endsection


@section('style')
    <style>
        .singleBlog-details .blogCaption .cartTop {
            margin-bottom: 16px;
        }
        .singleBlog-details .blogCaption .cartTop .listItmes {
            display: inline-block;
            margin-right: 10px;
            font-size: 16px;
            font-weight: 300;
        }
        .singleBlog-details .blogCaption .cartTop .listItmes .icon {
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')

    <section class="blogDetails section-padding">
        <div class="container">
            <div class="row justify-content-center flex-column-reverse flex-lg-row">

                <div class="col-lg-4">
                   <x-blog::frontend.sidebar-data/>
                </div>

                <div class="col-lg-8">
                    <article class="servicesDiscription-global">

                        <div class="capImg imgEffect">
                            {!! render_image_markup_by_attachment_id($blog_post->image) !!}
                        </div>
                    </article>
                    <div class="singleBlog-details">
                      <figcaption class="blogCaption">
                        <ul class="cartTop">
                            <li class="listItmes"><i class="fa-solid fa-calculator icon"></i> {{ get_date_by_format($blog_post->created_at) }}</li>
                            <li class="listItmes">
                                <a href="{{route('landlord.frontend.blog.category',['id'=> $blog_post->category_id, 'any' => \Illuminate\Support\Str::slug($blog_post->title)])}}">
                                    <i class="fa-solid fa-tag icon"></i> {{ $blog_post->category?->title }}
                                </a>
                            </li>
                            <li class="listItmes"><i class="fa-solid fa-eye icon"></i> {{$blog_post->views}}</li>
                            <li class="listItmes"><i class="fa-solid fa-comment icon"></i> {{ $blog_post->comments?->count() ??  0}} </li>
                        </ul>
                     </figcaption>
                    </div>

                    {!! $blog_post->getTranslation('blog_content',$user_lang) !!}

                    @php
                        $explode_tags = explode(',',$blog_post->tags) ?? [];
                    @endphp


                    <div class="row justify-content-between mb-20 mt-20">
                        <div class="col-lg-6 col-md-6">
                            <!-- Tag -->
                            @if(count($explode_tags) > 0 && !empty($explode_tags[0]))
                            <div class="tagArea mb-20">
                                    <ul class="selectTag">
                                        @foreach($explode_tags as $tag)
                                            <a href="{{route('landlord.frontend.blog.tags.page',['any' => $tag])}}">
                                                <li class="listItem">{{$tag}}</li>
                                            </a>
                                        @endforeach
                                    </ul>
                                 </div>
                            @else
                                <strong>{{__('No Tags')}}</strong>
                          @endif
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="footer-social float-md-end mb-40">
                                {!! single_blog_post_share(route(route_prefix().'frontend.blog.single',$blog_post->slug),$blog_post->getTranslation('title',$user_lang),$blog_post->image) !!}
                            </div>
                        </div>
                    </div>

                    <x-blog::frontend.comment-list :comments="$comments" :commentCount="$comments_count"/>

                    <x-blog::frontend.comment-form :blog="$blog_post"/>
                </div>
                    <x-blog::frontend.related-blog :allRelatedBlogs="$all_related_blogs"/>
            </div>
        </div>
     </div>
  </section>
@endsection

@section('scripts')
    @yield("custom-ajax-scripts")
    <script>
        $(document).on('click', '.load_more_button', function () {
            $(this).text('{{__('Loading...')}}');
            load_comment_data('{{$blog_post->id}}');
        });

        function load_comment_data(id) {
            var commentData = $('.comment_load_show');

            var items = commentData.attr('data-items');
            $.ajax({
                url: "{{ route(route_prefix().'frontend.load.blog.comment.data') }}",
                method: "POST",
                data: {id: id, _token: "{{csrf_token()}}", items: items},
                success: function (data) {
                    commentData.attr('data-items',parseInt(items) + 5);

                    $('.itemReview').append(data.markup);
                    $('.load_more_button').text('{{__('Load More')}}');


                    if (data.blogComments.length === 0) {
                        $('.load_more_button').text('{{__('No Comment Found')}}');
                    }

                }
            })
        }


        (function($){
            "use strict";


            $(document).ready(function(){
                //Blog Comment Insert
                $(document).on('click', '#submitComment', function (e) {
                    e.preventDefault();
                    var erContainer = $(".error-message");
                    var el = $(this);
                    var form = $('#blog-comment-form');
                    var user_id = form.find('input[name="user_id"]').val();
                    var blog_id = form.find('input[name="blog_id"]').val();
                    var comment_content = $('textarea[name="comment_content"]').val();

                    el.text('{{__('Submitting')}}...');

                    $.ajax({
                        url: '{{route(route_prefix().'frontend.blog.comment.store')}}',
                        method: 'POST',
                        data: {
                            _token: "{{csrf_token()}}",
                            user_id: user_id,
                            blog_id: blog_id,
                            comment_content: comment_content,
                        },
                        success: function (data){
                            $('textarea[name="comment_content"]').val('');
                            $('.itemReview').load(location.href + ' .itemReview');
                            el.text('{{__('Comment')}}');
                        },
                        error: function (data) {
                            var errors = data.responseJSON;
                            erContainer.html('<div class="alert alert-danger"></div>');
                            $.each(errors.errors, function (index, value) {
                                erContainer.find('.alert.alert-danger').append('<p>' + value + '</p>');
                            });
                            el.text('{{__('Comment')}}');
                        },

                    });
                });




            });
        })(jQuery);

    </script>

@endsection
