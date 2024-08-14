@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{$order_details->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('Order For')}} {{' : '.$order_details->getTranslation('title',get_user_lang())}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/common/css/toastr.css')}}">
@endsection

@section('content')
    @php
        $user_lang = get_user_lang();
    @endphp
    {{--==================================== NEW ==================================--}}
    <div class="packageArea section-padding">
        <div class="container">
            <div class="row g-4">

                @if(!auth()->guard('web')->check())
                    <div class="col-xl-8 col-lg-7 col-md-6">
                        <div class="packageWrapper">
                            <h2 class="text-center mb-4">{{__('Order Information')}}</h2>
                            <div class="login-form">
                                <form action="" method="post" enctype="multipart/form-data" class="contact-page-form style-01" id="login_form_order_page">
                                    @csrf
                                    <div class="alert alert-warning alert-block text-center">
                                        <strong >{{ __('You must login or create an account to order your package!') }}</strong>
                                    </div>
                                    <div class="error-wrap"></div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="input-form input-form2">
                                            <input type="text" name="username" class="form-control" placeholder="{{__('Username')}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="input-form input-form2">
                                            <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="btn-wrapper text-center">
                                            <button href="#" class="cmn-btn1 w-100" id="login_btn">{{__('Login')}}</button>
                                        </div>
                                    </div>

                                    <div class="other_if_not_login">
                                        <p class="sinUp mb-20"><span>{{__('Don’t have an account ?')}} </span>
                                            <a href="{{route('tenant.user.register')}}" class="singApp">{{__('Sign Up')}}</a></p>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                @else

                    @php
                        $user = Auth::guard('web')->user();
                    @endphp
                    <div class="col-xl-8 col-lg-7 col-md-6">
                        <div class="row ">

                            <div class="packageWrapper">
                            <h2 class="text-center mb-4">{{__('Order Details ')}}</h2>
                                <x-flash-msg/>
                                <x-error-msg/>

                                <form action="{{ route('tenant.wedding.frontend.order.payment.form') }}" method="post" enctype="multipart/form-data" class="contact-page-form style-01 order_page_form">
                                    @csrf

                                    @php
                                        $payment_gateway = !empty($custom_fields['selected_payment_gateway']) ? $custom_fields['selected_payment_gateway'] : '';
                                        $auth_check = auth()->guard('web')->check();
                                        $auth_user = auth()->guard('web')->user();
                                        $name = $auth_check ? $auth_user->name : '';
                                        $email = $auth_check ? $auth_user->email : '';
                                    @endphp
                                    <input type="hidden" name="payment_gateway" value="" class="payment_gateway_passing_clicking_name">
                                    <input type="hidden" name="package_id" value="{{$order_details->id}}">

                                    <div class="col-lg-12 col-md-12">
                                        <div class="input-form input-form2">
                                            <input type="text" name="name" value="{{$name}}" placeholder="Enter your name">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="input-form input-form2">
                                            <input type="email" name="email" value="{{$email}}" placeholder="Enter your email address">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="input-form input-form2">
                                            <label for="" class="text-primary">{{__('Payable Amount')}}</label>
                                            <input type="number" name="" value="{{$order_details->price}}" readonly>
                                        </div>
                                    </div>


                                    <div class="col-sm-12 mt-4">
                                        {!! render_payment_gateway_for_form() !!}
                                    </div>
                                    @php
                                        $data = \App\Models\PaymentGateway::where('name','manual_payment_')->first();
                                        $manual_payment_date_of_renew = request()->get('manual_attachment');
                                        $manual_payment_date_of_renew_condition = !empty($manual_payment_date_of_renew) ? $manual_payment_date_of_renew : '';
                                    @endphp

                                    <div class="col-sm-12 mt-4">
                                        <div class="btn-wrapper text-center">
                                            <button type="submit" class="cmn-btn1 w-100">{{__('Order Package')}}</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                    @php
                        $popular_condition_text = $order_details->is_popular == 'on' ? __('Popular') : '';
                        $popular_condition = $order_details->is_popular == 'on' ? 'popular' : '';
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="wedding_package {{$popular_condition}} radius-10">
                            <div class="wedding_package__header">
                                <h4 class="wedding_package__name">{{$order_details->title}}<span class="popular__title">{{$popular_condition_text}}</span></h4>
                                <h5 class="wedding_package__price"><sup>{{ site_currency_symbol() }}</sup>{{$order_details->price}}</h5>
                            </div>
                            <div class="wedding_package__body mt-4">
                                <ul class="wedding_package__list list-none">
                                    @php
                                        $fall_back_features =
                                         'This is demo
                                         feature please
                                          change this';

                                        $features_condition = !empty($order_details->features) ? explode(",",$order_details->features) : explode("\n",$fall_back_features);
                                        $not_available_features_condition = !empty($order_details->not_available_features) ? explode(",",$order_details->not_available_features) : explode("\n",$fall_back_features);
                                    @endphp

                                    @foreach($features_condition as  $feature)
                                        <li class="check_icon">{!! $feature !!}</li>
                                    @endforeach

                                    @foreach($not_available_features_condition as $not_feature)
                                        <li class="close_icon">{!! $not_feature !!}</li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{global_asset('assets/common/js/toastr.min.js')}}"></script>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function ($) {

                $(document).on('click', '#order_pkg_btn', function () {
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
                        url: "{{route('tenant.user.ajax.login')}}",
                        data: {
                            _token: "{{csrf_token()}}",
                            username: username,
                            password: password,
                            remember: remember,
                        },
                        success: function (data) {
                            if (data.status == 'invalid') {
                                el.text('{{__("Login")}}')
                                formContainer.find('.error-wrap').html('<div class="alert alert-danger">' + data.msg + '</div>');
                            } else {
                                formContainer.find('.error-wrap').html('');
                                el.text('{{__("Login Success.. Redirecting ..")}}');
                                location.reload();
                            }
                        },
                        error: function (data) {
                            var response = data.responseJSON.errors
                            formContainer.find('.error-wrap').html('<ul class="alert alert-danger"></ul>');
                            $.each(response, function (value, index) {
                                formContainer.find('.error-wrap ul').append('<li>' + index + '</li>');
                            });
                            el.text('{{__("Login")}}');
                        }
                    });
                });

                var defaulGateway = $('#site_global_payment_gateway').val();
                $('.payment-gateway-list ul li[data-gateway="' + defaulGateway + '"]').addClass('selected');

                $(document).on('click', '.payment-gateway-list > li', function (e) {
                    e.preventDefault();
                    let gateway = $(this).data('gateway');
                    $('#slected_gateway_from_helper').val(gateway)

                    $(this).addClass('selected').siblings().removeClass('selected');
                    $('.payment-gateway-list').find('input').val($(this).data('gateway'));
                    $('.payment_gateway_passing_clicking_name').val(gateway);
                });

                $(document).on('click', '.payment-gateway-list > li', function (e) {
                    e.preventDefault();

                    let gateway = $(this).data('gateway');
                    if (gateway === 'kinetic') {

                        $('.kinetic_payment_field').removeClass('d-none');
                    } else {
                        $('.kinetic_payment_field').addClass('d-none');
                    }

                    $(this).addClass('selected').siblings().removeClass('selected');
                    $('.payment-gateway-list').find(('input')).val($(this).data('gateway'));
                    $('.payment_gateway_passing_clicking_name').val(gateway);
                });



            });
        })(jQuery);
    </script>

@endsection
