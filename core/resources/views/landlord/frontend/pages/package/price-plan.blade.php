@extends('landlord.frontend.frontend-page-master')

@section('title')
    {{$order_details->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('View Plan')}} {{' : '.$order_details->getTranslation('title',get_user_lang())}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/common/css/toastr.css')}}">
@endsection

@section('content')

    @php
        $selected_lang = get_user_lang();

        if (str_contains($data['title'], '{h}') && str_contains($data['title'], '{/h}'))
        {
            $text = explode('{h}',$data['title']);

            $highlighted_word = explode('{/h}', $text[1])[0];

            $highlighted_text = '<span class="section-shape">'. $highlighted_word .'</span>';
            $final_title = '<h2 class="title">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $data['title']).'</h2>';
        } else {
            $final_title = '<h2 class="title">'. $data['title'] .'</h2>';
        }
    @endphp


    <section class="pricing-area section-bg-1" data-padding-top="{{$data['padding_top']}}"
             data-padding-bottom="{{$data['padding_bottom']}}" id="{{$data['section_id']}}">
        <div class="container">
            <div class="section-title">
                {!! $final_title !!}
                <p class="section-para"> {{$data['subtitle']}} </p>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col-lg-6 mt-4">
                    <div class="pricing-tab-list center-text">
                        <ul class="tabs price-tab radius-10">
                            <li data-tab="tab-month" class="price-tab-list active"> {{__('Monthly')}}</li>
                            <li data-tab="tab-year" class="price-tab-list"> {{__('Yearly')}}</li>
                            <li data-tab="tab-lifetime" class="price-tab-list"> {{__('Lifetime')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            @foreach($data['all_price_plan'] as  $plan_type => $plan_items)
                @php
                    $id= '';
                    $active = '';
                    $period = '';
                    if($plan_type == 0){
                        $id = 'month';
                        $active = 'show active';
                        $period = __('/mo');
                    }elseif($plan_type == 1){
                        $id = 'year';
                         $period = __('/yr');
                    }else{
                         $id = 'lifetime';
                          $period = __('/lt');
                    }
                @endphp

                <div class="tab-content-item {{$active}}" id="tab-{{$id}}">
                    <div class="row mt-4">
                        @php
                            $plan_slogan = ['Small Business', 'Best Offer', 'Special'];
                        @endphp
                        @foreach($plan_items as $key => $price_plan_item)
                            @php $featured_condition = $key == 1 ? 'active' : '' @endphp

                            <div class="col-lg-4 col-md-6 mt-4">
                                <div class="single-price radius-10 {{$featured_condition}}">
                                    <span class="single-price-sub-title mb-5 radius-5"> {{$plan_slogan[$key]}} </span>
                                    <div class="single-price-top center-text">
                                    <span
                                        class="single-price-top-plan"> {{$price_plan_item->getTranslation('title', $selected_lang)}} </span>
                                        <h3 class="single-price-top-title mt-4"> {{amount_with_currency_symbol($price_plan_item->price)}}
                                            <sub>{{$period}}</sub></h3>
                                    </div>
                                    <ul class="single-price-list mt-4">
                                        @if(!empty($price_plan_item->page_permission_feature))
                                            <li class="single-price-list-item">
                                                <span class="check-icon"> <i class="las la-check"></i> </span>
                                                <span> <strong> {{ sprintf(__('Page Create %d'),$price_plan_item->page_permission_feature )}} </strong> </span>
                                            </li>
                                        @endif

                                        @if(!empty($price_plan_item->product_permission_feature))
                                            <li class="single-price-list-item">
                                                <span class="check-icon"> <i class="las la-check"></i> </span>
                                                <span> <strong> {{ sprintf(__('Product Create %d'),$price_plan_item->product_permission_feature )}} </strong> </span>
                                            </li>
                                        @endif

                                        @if(!empty($price_plan_item->blog_permission_feature))
                                            <li class="single-price-list-item">
                                                <span class="check-icon"> <i class="las la-check"></i> </span>
                                                <span> <strong> {{ sprintf(__('Blog Create %d'),$price_plan_item->blog_permission_feature )}} </strong> </span>
                                            </li>
                                        @endif
                                    </ul>
                                    <div class="btn-wrapper mt-4 mt-lg-5">
                                        @if($price_plan_item->has_trial == true)
                                            <div class="d-flex justify-content-center">
                                                <a href="{{route('landlord.frontend.plan.order',$price_plan_item->id)}}" class="cmn-btn cmn-btn-outline-one color-one w-100 mx-1">
                                                    {{__('Start Now')}} </a>

                                                <a href="{{route('landlord.frontend.plan.order',$price_plan_item->id)}}" class="cmn-btn cmn-btn-outline-one color-one w-100 mx-1">
                                                    {{__('Try Now')}} </a>
                                            </div>
                                        @else
                                            <a href="{{route('landlord.frontend.plan.order',$price_plan_item->id)}}" class="cmn-btn cmn-btn-outline-one color-one w-100">
                                                {{__('Start Now')}} </a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{global_asset('assets/common/js/toastr.min.js')}}"></script>
    <script>
        (function($){
            "use strict";
            $(document).ready(function ($) {

                $(document).on('click','#order_pkg_btn', function(){
                    var name = $("#order_name").val()
                    var email = $("#order_email").val()
                    sessionStorage.pkg_user_name = name;
                    sessionStorage.pkg_user_email = email;

                })

                $(document).on('click', '#login_btn', function (e) {
                    e.preventDefault();
                    var formContainer = $('#login_form_order_page');
                    var el = $(this);
                    var username = formContainer.find('input[name="username"]').val();
                    var password = formContainer.find('input[name="password"]').val();
                    var remember = formContainer.find('input[name="remember"]').val();

                    el.text('{{__("Please Wait")}}');

                    $.ajax({
                        type: 'post',
                        url: "{{route('landlord.user.ajax.login')}}",
                        data: {
                            _token: "{{csrf_token()}}",
                            username : username,
                            password : password,
                            remember : remember,
                        },
                        success: function (data){
                            if(data.status == 'invalid'){
                                el.text('{{__("Login")}}')
                                formContainer.find('.error-wrap').html('<div class="alert alert-danger">'+data.msg+'</div>');
                            }else{
                                formContainer.find('.error-wrap').html('');
                                el.text('{{__("Login Success.. Redirecting ..")}}');
                                location.reload();
                            }
                        },
                        error: function (data){
                            var response = data.responseJSON.errors
                            formContainer.find('.error-wrap').html('<ul class="alert alert-danger"></ul>');
                            $.each(response,function (value,index){
                                formContainer.find('.error-wrap ul').append('<li>'+index+'</li>');
                            });
                            el.text('{{__("Login")}}');
                        }
                    });
                });
                var defaulGateway = $('#site_global_payment_gateway').val();
                $('.payment-gateway-wrapper ul li[data-gateway="'+defaulGateway+'"]').addClass('selected');

                $(document).on('click','.payment-gateway-wrapper > ul > li',function (e) {
                    e.preventDefault();

                    let gateway =  $(this).data('gateway');
                    if(gateway === 'manual_payment_'){
                        $('.manual_transaction_id').removeClass('d-none');
                    }else{
                        $('.manual_transaction_id').addClass('d-none');
                    }

                    $(this).addClass('selected').siblings().removeClass('selected');
                    $('.payment-gateway-wrapper').find('input').val($(this).data('gateway'));
                    $('.payment_gateway_passing_clicking_name').val($(this).data('gateway'));

                });

                $(document).on('change','#guest_logout',function (e) {
                    e.preventDefault();
                    var infoTab = $('#nav-profile-tab');
                    var nextBtn = $('.next-step-button');
                    if($(this).is(':checked')){
                        $('.login-form').hide();
                        infoTab.attr('disabled',false).removeClass('disabled');
                        nextBtn.show();
                    }else{
                        $('.login-form').show();
                        infoTab.attr('disabled',true).addClass('disabled');
                        nextBtn.hide();
                    }
                });

                $(document).on('click','.next-step-button',function(e){
                    var infoTab = $('#nav-profile-tab');
                    infoTab.attr('disabled',false).removeClass('disabled').addClass('active').siblings().removeClass('active');
                    $('#nav-profile').addClass('show active').siblings().removeClass('show active');
                });

            });

        })(jQuery);
    </script>
@endsection
