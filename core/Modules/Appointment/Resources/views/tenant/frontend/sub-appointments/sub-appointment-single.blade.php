@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('page-title')
    {{ __('Sub Appointment') }} : {{ $sub_appointment->getTranslation('title',$user_lang) }}
@endsection

@section('title')
    {{ $sub_appointment->getTranslation('title',$user_lang) }}
@endsection

@section('meta-data')
    {!!  render_page_meta_data($sub_appointment) !!}
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
            color: var(--peragraph-color);
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')

    <section class="blogDetails section-padding">
        <div class="container">
            <div class="row justify-content-center flex-column-reverse flex-xxl-row">
                <div class="col-xxl-4 col-xl-4 col-lg-5 col-md-6">
                    <x-appointment::frontend.sub-appointment.sidebar-data/>
                </div>
                <div class="col-xxl-8 col-xl-9">
                    <article class="servicesDiscription-global">

                        <div class="capImg imgEffect">
                            {!! render_image_markup_by_attachment_id($sub_appointment->image) !!}
                        </div>
                    </article>
                    <div class="singleBlog-details">
                        <figcaption class="blogCaption">
                            <ul class="cartTop">
                                <li class="listItmes"><i class="fa-solid fa-calculator icon"></i> {{$sub_appointment->created_at?->format('d M, Y')}}</li>
                                <li class="listItmes"><i class="fa-solid fa-eye icon"></i> {{$sub_appointment->views}}</li>
                                <li class="listItmes"><i class="fa-solid fa-comment icon"></i> {{ $sub_appointment->comments?->count() ??  0}} </li>
                            </ul>
                        </figcaption>
                    </div>

                    {!! $sub_appointment->getTranslation('description',$user_lang) !!}

                    <div class="row justify-content-between mb-20 mt-20">
                        <div class="col-lg-6 col-md-6">
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="footer-social float-md-end mb-40">
                                {!! single_blog_post_share(route('tenant.frontend.sub.appointment.single',$sub_appointment->slug),$sub_appointment->getTranslation('title',$user_lang),$sub_appointment->image) !!}
                            </div>
                        </div>
                    </div>

                    <x-appointment::frontend.sub-appointment.comment-list :comments="$comments" :commentCount="$comments_count"/>

                    <x-appointment::frontend.sub-appointment.comment-form :subappointment="$sub_appointment"/>
                </div>
            </div>
            <x-appointment::frontend.sub-appointment.related-appointment :allRelatedSubAppointments="$all_related_sub_appointments"/>
        </div>
        </div>
    </section>
@endsection

@section('scripts')
    @yield("custom-ajax-scripts")
    <script>
        $(document).on('click', '.load_more_button', function () {
            $(this).text('{{__('Loading...')}}');
            load_comment_data('{{$sub_appointment->id}}');
        });

        function load_comment_data(id) {
            var commentData = $('.comment_load_show');

            var items = commentData.attr('data-items');
            $.ajax({
                url: "{{ route(route_prefix().'frontend.load.sub.appointment.comment.data') }}",
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
                    var sub_appointment_id = form.find('input[name="sub_appointment_id"]').val();
                    var comment_content = $('textarea[name="comment_content"]').val();

                    el.text('{{__('Submitting')}}...');

                    $.ajax({
                        url: '{{route('tenant.frontend.sub.appointment.comment.store')}}',
                        method: 'POST',
                        data: {
                            _token: "{{csrf_token()}}",
                            user_id: user_id,
                            sub_appointment_id: sub_appointment_id,
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
