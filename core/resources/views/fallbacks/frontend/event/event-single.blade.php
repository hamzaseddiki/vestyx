@extends('tenant.frontend.frontend-page-master')

@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('page-title')
    {{ $event->getTranslation('title',$user_lang) }}
@endsection

@section('title')
    {{ $event->getTranslation('title',$user_lang) }}
@endsection

@section('meta-data')
    {!!  render_page_meta_data($event) !!}
@endsection

@section('content')
    <div class="detailsCap section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <!-- Single -->
                    <div class="capDetails  mb-10">
                        <div class="blog-img imgEffect">
                            {!!  render_image_markup_by_attachment_id($event->image,'','grid') !!}
                        </div>
                    </div>
                    <!-- Review  -->
                    <div class="ourReview">
                        <div class="nav-button mb-30">
                            <!--Nav Button  -->
                            <nav>
                                <div class="nav nav-tabs " id="nav-tab" role="tablist">
                                    <a class="nav-link active" id="nav-one-tab" data-bs-toggle="tab" href="#nav-description" role="tab" aria-controls="nav-one" aria-selected="true">{{get_static_option('event_tab_description_'.get_user_lang().'_title',__('Description'))}}</a>
                                    <a class="nav-link" id="nav-three-tab" data-bs-toggle="tab" href="#nav-comments" role="tab" aria-controls="nav-three" aria-selected="false">{{get_static_option('event_tab_comment_'.get_user_lang().'_title',__('Comment'))}}</a>
                                    <a class="nav-link" href="{{route('tenant.frontend.event.payment',$event->slug)}}" >{{get_static_option('event_tab_book_'.get_user_lang().'_title',__('Comment'))}}</a>
                                </div>
                            </nav>
                            <!--End Nav Button  -->
                        </div>
                        <!-- Nav Card -->
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane lade show active" id="nav-description" role="tabpanel" aria-labelledby="nav-one-tab">
                                <!-- Tab 1 -->
                                <div class="capDescription">
                                    <div class="single mb-24">
                                        {!! $event->getTranslation('content',get_user_lang()) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane lade" id="nav-comments" role="tabpanel" aria-labelledby="nav-two-tab">
                                <!-- Tab 2 -->
                                <div class="capDescription">
                                    <div class="single mb-24">
                                        <x-event::frontend.event.comment-data :comments="$comments" :commentCount="$comments_count"/>
                                        <x-event::frontend.event.comment-form :event="$event"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="simplePresentCart mb-30">
                        @if(!empty(get_static_option('event_map_area_show_hide')))
                            <x-event::frontend.event.map-area :event="$event"/>
                        @endif
                        @if(!empty(get_static_option('event_chart_area_show_hide')))
                            <x-event::frontend.event.chart-area/>
                        @endif
                        @if(!empty(get_static_option('event_social_area_show_hide')))
                                <x-event::frontend.event.social-share-area :event="$event"/>
                        @endif
                        @if(!empty(get_static_option('event_category_area_show_hide')))
                            <x-event::frontend.event.category-area :categories="$all_related_categories"/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty(get_static_option('event_related_area_show_hide')))
        <x-event::frontend.event.related-event :relatedEvents="$all_related_events"/>
    @endif

@endsection


@section('scripts')
    <script src="{{global_asset('assets/common/js/countdown.jquery.js')}}"></script>
    @yield("custom-ajax-scripts")
    <script>
        $(document).on('click', '.load_more_button', function () {
            $(this).text('{{__('Loading...')}}');
            load_comment_data('{{$event->id}}');
        });

        function load_comment_data(id) {
            var commentData = $('.comment_load_show');

            var items = commentData.attr('data-items');

            $.ajax({
                url: "{{ route('tenant.frontend.load.event.comment.data') }}",
                method: "POST",
                data: {
                    id: id,
                    _token: "{{csrf_token()}}",
                    items: items
                },
                success: function (data) {
                    commentData.attr('data-items',parseInt(items) + 5);

                    $('.itemReview').append(data.markup);
                    $('.load_more_button').text('{{__('Load More')}}');


                    if (data.donationComments.length === 0) {
                        $('.load_more_button').text('{{__('No Comment Found')}}');
                    }

                }
            })
        }
                //Blog Comment Insert
                $(document).on('click', '#submitComment', function (e) {
                    e.preventDefault();
                    var erContainer = $(".error-message");
                    var el = $(this);
                    var form = $('#blog-comment-form');
                    var user_id = form.find('input[name="user_id"]').val();
                    var event_id = form.find('input[name="event_id"]').val();
                    var comment_content = $('textarea[name="comment_content"]').val();

                    el.text('{{__('Submitting')}}...');

                    $.ajax({
                        url: '{{route('tenant.frontend.event.comment.store')}}',
                        method: 'POST',
                        data: {
                            _token: "{{csrf_token()}}",
                            user_id: user_id,
                            event_id: event_id,
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

    </script>


    <script>

        let chart_event_data = `{!! json_encode($new_chart_array) !!}`;

        new Chart(document.getElementById("line-chart"), {
            type: 'line',
            data: {
                labels: ['Sat', 'Sun', "Mon", 'Tue', 'Web', "Thu", "Fri"],
                datasets: [
                    {
                        data: chart_event_data,
                        label: "Sale",
                        borderColor: "#FF99AE",
                        borderWidth: 1,
                        fill: true,

                        pointBorderWidth: 1,
                        pointBackgroundColor: '#fff',
                        pointRadius: 5,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "#1DBF73",

                        backgroundColor: 'rgba(255, 82, 41,.2)',
                    }
                ],
            },

            options:{
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                }
            }

        });
    </script>
@endsection
