@extends('tenant.frontend.frontend-page-master')

@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('page-title')
    {{ $donation->getTranslation('title',$user_lang) }}
@endsection

@section('title')
    {{ $donation->getTranslation('title',$user_lang) }}
@endsection

@section('meta-data')
    {!!  render_page_meta_data($donation) !!}
@endsection

@section('content')
    <style>

        .donation_countdown {
            display: flex;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
        }
        .donation_countdown .nx-singular-countdown-item {
            background: var(--main-color-one);
            display: inline-block;
            text-align: center;
            height: 102px;
            width: 102px;
            padding: 14px 10px;
        }

        .donation_countdown .nx-singular-countdown-item  .wrapper {
            font-family: var(--heading-font);
            font-size: 16px;
            margin-bottom: 0;
            color: #ffffff;
            font-weight: 500;
        }
        .donation_countdown .nx-singular-countdown-item  .wrapper .time {
            font-family: var(--heading-font);
            font-size: 20px;
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 9px;
            display: block;
        }
        .donation_countdown .nx-singular-countdown-item  .wrapper .label {
            font-family: var(--heading-font);
            font-size: 16px;
            margin-bottom: 0;
            color: #ffffff;
            font-weight: 500;
        }
        .btn-wrapper .cmn-btn-outline3 {
            font-family: var(--heading-font);
            border: 1px solid var(--main-color-one);
            color: var(--main-color-one);
            font-size: 15px;
            font-weight: 500;
            text-transform: normal;
            padding: 14px 29px !important;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            text-align: center;
            -moz-user-select: none;
            cursor: pointer;
            display: inline-block;
            position: relative;
            -webkit-transition: color 0.4s linear;
            transition: color 0.4s linear;
            position: relative;
            overflow: hidden;
            border-radius: 0px;
            background: none;
            z-index: 1;
        }
        .btn-wrapper .cmn-btn-outline3 .icon {
            font-size: 18px;
            margin-right: 6px;
        }
        .btn-wrapper .cmn-btn-outline3::before {
            border: 1px solid transparent;
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 101%;
            height: 101%;
            background: var(--main-color-one);
            z-index: -1;
            -webkit-transition: -webkit-transform 0.5s;
            transition: -webkit-transform 0.5s;
            transition: transform 0.5s;
            transition: transform 0.5s, -webkit-transform 0.5s;
            -webkit-transition-timing-function: ease;
            transition-timing-function: ease;
            -webkit-transform-origin: 0 0;
            transform-origin: 0 0;
            -webkit-transition-timing-function: cubic-bezier(0.5, 1.6, 0.4, 0.7);
            transition-timing-function: cubic-bezier(0.5, 1.6, 0.4, 0.7);
            -webkit-transform: scaleX(0);
            transform: scaleX(0);
            border-radius: 0;
        }
        .btn-wrapper .cmn-btn-outline3:hover {
            color: #ffffff;
        }
        .btn-wrapper .cmn-btn-outline3:hover::before {
            -webkit-transform: scaleX(1);
            transform: scaleX(1);
            border: 1px solid transparent;
        }
    </style>
    <div class="detailsCap section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="capDetails mb-10">
                        <div class="blog-img overlay1">
                            {!! render_image_markup_by_attachment_id($donation->image) !!}
                        </div>
                        <div class="caption">
                            <!-- Blog Footer -->
                            <div class="singleComments">
                                <div class="blogPostUser mb-20">
                                    {!! render_image_markup_by_attachment_id($donation->creator?->image) !!}
                                    <div class="cap">
                                        <a href="#" class="tittle">{{$donation->creator?->name}}</a>
                                        <p>{{__('Admin')}}</p>
                                    </div>
                                </div>
                                <ul class="cartTop">
                                    <li class="listItmes"><i class="fa-regular fa-clock icon"></i>{{$donation->created_at?->diffForHumans()}}</li>

                                    <a href="{{route('tenant.frontend.donation.category', ['id' => $donation->category?->id,'any' => Str::slug($donation->category?->getTranslation('title',get_user_lang()))])}}">
                                      <li class="listItmes">
                                          <i class="fa-solid fa-tag icon"></i> {{$donation->category?->getTranslation('title',get_user_lang())}}
                                      </li>
                                    </a>


                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="ourReview">
                        <div class="nav-button mb-30">
                            <nav>
                                <div class="nav nav-tabs " id="nav-tab" role="tablist">
                                    <a class="nav-link active" id="nav-one-tab" data-bs-toggle="tab" href="#nav-one" role="tab" aria-controls="nav-one" aria-selected="true">{{__('Description')}}</a>

                                    @if(!empty(get_static_option('donation_faq_show_hide')))
                                    <a class="nav-link" id="nav-two-tab" data-bs-toggle="tab" href="#nav-two" role="tab" aria-controls="nav-two" aria-selected="false">{{__('FAQ')}}</a>
                                    @endif

                                    @if(!empty(get_static_option('donation_comments_show_hide')))
                                    <a class="nav-link" id="nav-three-tab" data-bs-toggle="tab" href="#nav-three" role="tab" aria-controls="nav-three" aria-selected="false">{{__('Comments')}}</a>
                                    @endif

                                </div>
                            </nav>
                        </div>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-one" role="tabpanel" aria-labelledby="nav-one-tab">
                                <!-- DESCRIPTION TAB -->
                                <div class="capDescription">
                                    <div class="single mb-24">
                                        {!! $donation->getTranslation('description',get_user_lang()) !!}
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade show" id="nav-two" role="tabpanel" aria-labelledby="nav-one-tab">
                                <!-- FAQ TAB  -->
                                <div class="capDescription">
                                    <div class="single mb-24">
                                        <x-donation::frontend.donation.faq-data :donation="$donation"/>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="nav-three" role="tabpanel" aria-labelledby="nav-one-tab">
                                <!-- COMMENTS TAB -->
                                <div class="capDescription">
                                    <div class="single mb-24">
                                        <x-donation::frontend.donation.comment-data :comments="$comments" :commentCount="$comments_count"/>
                                        <x-donation::frontend.donation.comment-form :donation="$donation"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="simplePresentCart mb-30">

                    @if(!empty(get_static_option('donation_single_page_countdown_status')))
                        @if($donation->deadline > now())
                           <x-donation::frontend.donation.timer-data/>
                        @endif
                    @endif

                        <div class="prices2 mb-10">
                            <span class="price">{{ get_static_option('donation_raised_'.get_user_lang().'_text') }} {{amount_with_currency_symbol($donation->raised)}}</span>
                            <span class="dates"> {{get_static_option('donation_goal_'.get_user_lang().'_text')}} {{ amount_with_currency_symbol($donation->amount) }}</span>
                        </div>

                        <div class="singleProgres mb-40" aria-valuenow="30">
                            <div class="bar-1 donation-progress" data-percentage="{{get_percentage($donation->amount,$donation->raised)}}"></div>
                        </div>

                        <div class="btn-wrapper mb-40">
                            @if($donation->deadline > now())
                               <a href="{{route('tenant.frontend.donation.payment',$donation->id)}}" class=" test_css cmn-btn-outline3 w-100">{{__('Donate Now')}}</a>
                            @else
                               <a href="#!" class="cmn-btn-outline3 w-100">{{__('Donation Expired')}}</a>
                             @endif
                        </div>

                        @if(!empty(get_static_option('donation_social_icons_show_hide')))
                            <x-donation::frontend.donation.social-share :donation="$donation"/>
                        @endif

                        <div class="textarea-form mb-10">
                            <h2 class="infoTitle2 mb-30">{{__('Get Embed Code')}}</h2>
                            <input type="hidden" data-url="{{route('tenant.frontend.donation.single',$donation->slug)}}" class="form-control" id="donation_copy_id">
                            <textarea class=" embed_area donation_get_embed_code_box"></textarea>
                        </div>
                        <div class="btn-wrapper">
                            <a href="#!" class="cmn-btn4 w-100 copy_embed_button">{{__('Copy')}}</a>
                        </div>
                    </div>

                    @if(!empty(get_static_option('donation_recent_donors_show_hide')))
                      <x-donation::frontend.donation.donor-data :allDonors="$all_donors_data" />
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{global_asset('assets/common/js/countdown.jquery.js')}}"></script>
    @yield("custom-ajax-scripts")
    <script>
        $(document).on('click', '.load_more_button', function () {
            $(this).text('{{__('Loading...')}}');
            load_comment_data('{{$donation->id}}');
        });

        function load_comment_data(id) {
            var commentData = $('.comment_load_show');

            var items = commentData.attr('data-items');
            $.ajax({
                url: "{{ route('tenant.frontend.load.donation.comment.data') }}",
                method: "POST",
                data: {id: id, _token: "{{csrf_token()}}", items: items},
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
                    var donation_id = form.find('input[name="donation_id"]').val();
                    var comment_content = $('textarea[name="comment_content"]').val();

                    el.text('{{__('Submitting')}}...');

                    $.ajax({
                        url: '{{route('tenant.frontend.donation.comment.store')}}',
                        method: 'POST',
                        data: {
                            _token: "{{csrf_token()}}",
                            user_id: user_id,
                            donation_id: donation_id,
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

        var ev_offerTime = "{{$donation->deadline}}";
        var ev_year = ev_offerTime.substr(0, 4);
        var ev_month = ev_offerTime.substr(5, 2);
        var ev_day = ev_offerTime.substr(8, 2);

        if (ev_offerTime) {
            $('.donation_countdown').countdown({
                year: ev_year,
                month: ev_month,
                day: ev_day,
                labels: true,
                labelText: {
                    'days': "{{__('days')}}",
                    'hours': "{{__('hours')}}",
                    'minutes': "{{__('min')}}",
                    'seconds': "{{__('sec')}}",
                }
            });
        }


        //Copy Embed Code
        var url = $('#donation_copy_id').data(url);
        var copy_field = $('.donation_get_embed_code_box').val(url.url);
        let imf_container = '<iframe class="iframe_field" width="560" height="315" src="'+copy_field.val()+'" frameborder="0" '+
            'allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        $('.embed_area').val(imf_container);

        $(document).on('click','.copy_embed_button',function(){
            navigator.clipboard.writeText(imf_container)
            $(this).html('<i class="fas fa-check "> {{__('copied')}}</i>');
            setTimeout(function(){
                $('.copy_embed_button').text('Copy');
            },2000);
        });

    </script>
@endsection
