@extends(route_prefix().'frontend.frontend-page-master')

@section('title')
    {{__('Checkout')}}
@endsection

@section('page-title')
    {{__('Checkout')}}
@endsection

@section('style')
    <style>
        .payment-gateway-wrapper ul {
            flex-wrap: wrap;
            display: flex;
        }

        .payment-gateway-wrapper ul li {
            max-width: 100px;
            cursor: pointer;
            box-sizing: border-box;
            height: 50px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .payment-gateway-wrapper ul li {
            margin: 3px;
            border: 1px solid #ddd;
        }


        .payment-gateway-wrapper ul li.selected:after, .payment-gateway-wrapper ul li.selected:before {
            visibility: visible;
            opacity: 1;
        }

        .payment-gateway-wrapper ul li:before {
            border: 2px solid #930ed8;
            position: absolute;
            right: 0;
            top: 0;
            width: 100%;
            height: 100%;
            content: '';
            visibility: hidden;
            opacity: 0;
            transition: all .3s;
        }

        .payment-gateway-wrapper ul li.selected:after, .payment-gateway-wrapper ul li.selected:before {
            visibility: visible;
            opacity: 1;
        }

        .payment-gateway-wrapper ul li:after {
            position: absolute;
            right: 0;
            top: 0;
            width: 15px;
            height: 15px;
            background-color: #930ed8;
            content: "\f00c";
            font-weight: 900;
            color: #fff;
            font-family: 'Line Awesome Free';
            font-weight: 900;
            font-size: 10px;
            line-height: 10px;
            text-align: center;
            padding-top: 2px;
            padding-left: 2px;
            visibility: hidden;
            opacity: 0;
            transition: all .3s;
        }
        .plan_warning small{
            font-size: 15px;
        }
        .coupon-radio-item {
            display: flex;
            align-items: baseline;
            gap: 5px;
        }
        .coupon-radio-item input {
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 16px;
            height: 16px;
            border: 1px solid currentColor;
            border-radius: 50%;
            position: relative;
            transition: all .2s;
        }
        .coupon-radio-item input:before {
            content: "";
            position: absolute;
            height: calc(100% - 6px);
            width: calc(100% - 6px);
            top: 3px;
            left: 3px;
            background-color: var(--main-color-one);
            transform: scale(0);
            border-radius: 50%;
            transition: all .2s;
        }
        .coupon-radio-item input:checked::before {
            transform: scale(1);
        }
        .coupon-radio-item input:checked {
            border-color: var(--main-color-one);
        }
        .coupon-contents-details-list-item {
            font-size: 16px;
            padding: 7px 0;
        }

        .coupon-contents-details-list {
            padding: 10px 0;
        }
        .coupon-contents-details-list > h6 {
            padding-bottom: 15px;
        }

        .checkout-form-flex {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            gap: 12px 24px;
        }

        .single-input {
            position: relative;
            z-index: 1;
        }

        .single-input {
            display: inline-block;
            width: 100%;
        }

        .checkout-form .single-input .label-title {
            font-size: 15px;
        }

        .single-input .label-title {
            font-size: 16px;
            line-height: 28px;
            font-weight: 500;
            display: block;
            margin-bottom: 12px;
            color: var(--heading-color);
        }

        .checkout-form .single-input .form--control {
            height: 55px;
            width: 100%;
            border: 1px solid rgba(221, 221, 221, 0.4);
            -webkit-box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            -webkit-transition: 300ms;
            transition: 300ms;
            font-size: 15px;
            color: var(--extra-light-color);
        }

        .checkout-form .single-input .form-control {
            height: 55px;
            width: 100%;
            border: 1px solid rgba(221, 221, 221, 0.4);
            -webkit-box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            -webkit-transition: 300ms;
            transition: 300ms;
            font-size: 15px;
            color: var(--extra-light-color);
        }

        .checkout-form .single-input .form--message {
            height: 150px;
        }
        .single-input .label {
            font-size: 16px;
            line-height: 28px;
            font-weight: 500;
            display: block;
            margin-bottom: 12px;
        }

        .checkout-inner-title {
            font-size: 28px;
            line-height: 36px;
            margin: -8px 0 0;
        }

        .border-1 {
            border: 1px solid rgba(221, 221, 221, 0.5);
        }

        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            outline: none;
            -moz-osx-font-smoothing: grayscale;
            -webkit-font-smoothing: antialiased;
        }

        .summery-title {
            font-size: 28px;
            line-height: 36px;
            margin: -5px 0 0;
        }

        .checkout-order-summery {
            position: sticky;
            top: 0;
            padding: 40px;
        }

        .coupon-contents-form {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            gap: 15px;
        }

        .coupon-contents-form .single-input {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }

        .single-input {
            position: relative;
            z-index: 1;
        }

        .coupon-contents-form .btn-submit {
            padding: 12px 20px;
            font-size: 16px;
            background: none;
            border: 2px solid var(--main-color-two);
            -webkit-transition: 300ms;
            transition: 300ms;
            color: var(--main-color-two);
        }
        .coupon-contents-form .btn-submit:hover {
            background-color: var(--main-color-two);
            color: #fff;
        }
        .single-input .form--control {
            width: 100%;
            height: 55px;
            line-height: 55px;
            padding: 0 15px;
            border: 1px solid rgba(221, 221, 221, 0.3);
            background-color: unset;
            outline: none;
            color: var(--paragraph-color);
            -webkit-transition: 300ms;
            transition: 300ms;
            -webkit-box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }
        .single-input .form--control:focus {
            border-color: rgba(var(--main-color-one-rgb), 0.3);
            -webkit-box-shadow: 0 0 10px rgba(var(--main-color-one-rgb), 0.1);
            box-shadow: 0 0 10px rgba(var(--main-color-one-rgb), 0.1);
        }
        .single-input .form--control {
            border: 1px solid rgba(221, 221, 221, 0.4);
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
            -webkit-box-shadow: 0 0 10px transparent;
            box-shadow: 0 0 10px transparent;
        }

        .single-checkout-cart-items .single-check-carts .check-cart-flex-contents .checkout-cart-thumb img {
            height: 80px;
            -o-object-fit: cover;
            object-fit: cover;
        }

        .single-checkout-cart-items .single-check-carts .check-cart-flex-contents .checkout-cart-img-contents {
            text-align: left;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }

        .single-checkout-cart-items .single-check-carts .check-cart-flex-contents .checkout-cart-img-contents .product-items {
            margin-top: 10px;
        }

        .single-checkout-cart-items .single-check-carts .checkout-cart-price {
            font-size: 18px;
        }

        .coupon-contents-details-list {
            padding: 10px 0;
        }

        .coupon-contents-details-list-item {
            font-size: 16px;
            padding: 7px 0;
        }

        .coupon-contents-details-list {
            padding: 10px 0;
        }

        .coupon-border {
            border-top: 1px solid rgba(221, 221, 221, 0.4);
        }

        .checkbox-inlines {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            cursor: pointer;
            gap: 10px;
        }

        .checkbox-inlines .checkbox-label {
            cursor: pointer;
            text-align: left;
            line-height: 26px;
            font-size: 16px;
            font-weight: 400;
            color: var(--heading-color);
            margin: 0;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }

        .single-checkout-cart-items .single-check-carts .check-cart-flex-contents .checkout-cart-thumb {
            height: 95px;
            width: 100px;
        }

        .single-checkout-cart-items .single-check-carts {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }

        .single-checkout-cart-items .single-check-carts .check-cart-flex-contents {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            gap: 10px;
        }

        .coupon-contents-details-list-item {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: 10px 0;
            color: var(--light-color);
            font-size: 18px;
        }

        .coupon-contents-details-list-item {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: 10px 0;
            color: var(--light-color);
            font-size: 18px;
        }

        .checkbox-inlines {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            cursor: pointer;
            gap: 10px;
        }

        .checkbox-inlines .check-input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            height: 18px;
            width: 18px;
            cursor: pointer;
            background: #fff;
            border: 1px solid #dddddd;
            border-radius: 0px;
            margin-top: 4px;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
        }

        .checkbox-inlines .checkbox-label {
            cursor: pointer;
            text-align: left;
            line-height: 26px;
            font-size: 16px;
            font-weight: 400;
            color: var(--heading-color);
            margin: 0;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }

        .color-heading {
            color: var(--heading-color);
        }

        .checkout-title .click-open-form {
            color: var(--main-color-two);
        }

        .checkbox-inlines .check-input:checked::after {
            visibility: visible;
            opacity: 1;
            -webkit-transform: scale(1.2) rotate(0deg);
            transform: scale(1.2) rotate(0deg);
        }

        .checkbox-inlines .check-input::after {
            content: "";
            font-family: "Line Awesome Free";
            font-weight: 900;
            font-size: 10px;
            color: #fff;
            visibility: hidden;
            opacity: 0;
            -webkit-transform: scale(1.6) rotate(90deg);
            transform: scale(1.6) rotate(90deg);
            -webkit-transition: all 0.2s;
            transition: all 0.2s;
        }

        .checkout-order-summery .checkbox-inlines .check-input:checked {
            background-color: var(--main-color-two);
            border-color: var(--main-color-two);
        }

        .create-accounts {
            font-size: 20px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap;
            gap: 10px;
            color: var(--heading-color) !important;
        }

        .create-accounts::before {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            height: 20px;
            width: 20px;
            border: 1px solid #DDD;
            content: "";
            -webkit-transition: all 0.2s;
            transition: all 0.2s;
        }



        .create-accounts.active::before {
            content: "";
            font-family: "Line Awesome Free";
            font-weight: 900;
            background: var(--main-color-two);
            color: #fff;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-size: 12px;
            border-color: var(--main-color-two);
        }
        .checkout-form-contents.form-padding {
            padding: 40px;
        }
        .checkout-form-contents-title {
            font-size: 24px;
            line-height: 32px;
            margin: -6px 0 15px;

        }
        .form-group:not(:last-child) {
            margin-bottom: 24px;
        }

        .form-group .form-control {
            width: 100%;
            height: 55px;
            line-height: 55px;
            padding: 0 15px;
            background-color: unset;
            outline: none;
            color: var(--light-color);
            border: 1px solid rgba(221,221,221,.4);
            -webkit-transition: all .3s;
            transition: all .3s;
            -webkit-box-shadow: 0 0 10px transparent;
            box-shadow: 0 0 10px transparent;
        }

        .checkout-form-open-signin {
            display: none;
            margin-top: 30px;
        }

        .checkout-form-open-register {
            display: none;
            margin-top: 10px;
        }




    </style>
@endsection

@section('content')
    @if(\Gloudemans\Shoppingcart\Facades\Cart::count() > 0)
        <div class="checkout-area padding-top-75 padding-bottom-50">
            <div class="container container-one">
                <x-error-msg/>
                <div class="row">
                    @include('product::frontend.shop.checkout.partials.checkout_left_side')

                    @include('product::frontend.shop.checkout.partials.checkout_right_side')
                </div>
            </div>
        </div>
    @else
        @include('product::frontend.shop.cart.cart_empty')
    @endif

@endsection

@section('scripts')
    <script>
        $(function (){


            $(document).on('click', '.click-open-form', function() {
                $('.checkout-form-open-signin').fadeToggle(500);
                $('.checkout-form-open-signin').toggleClass('active');
            });

            $(document).on('click', '.click-open-form2', function() {
                $(this).toggleClass('active');
                $('.checkout-form-open-register').fadeToggle(500);
                $('.checkout-form-open-register').toggleClass('active');
            });

            $(document).on('click', '.click-open-form3', function() {
                $(this).toggleClass('active');
                $('.checkout-address-form-wrapper').toggleClass('active');
            });

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
                        if (data.status === 'invalid') {
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

            $(document).on('click', '.shift-another-address', function (){
                let el = $(this);

                let $items;
                if (el.hasClass('active')) {
                    $items = $('.shift-address-form input');
                    $.each($items, function (key, value){
                        $(value).val('');
                    });

                    $('.shift_another_address').val('on');
                }

                if (el.hasClass('active') === false) {
                    $('.shift_another_address').val('');
                }
            });

            $(document).on('change', 'select[name=shift_country]', function (e){
                let el = $(this);
                let country = el.val();

                $.ajax({
                    url: '{{route('tenant.shop.checkout.state.ajax')}}',
                    type: 'GET',
                    data: {
                        country: country
                    },

                    beforeSend: () => {
                        el.parent().parent().find('.shift-another-state').html('');
                        CustomLoader.start();
                    },
                    success: (data) => {
                        el.parent().parent().find('.shift-another-state').html(data.markup);
                        CustomLoader.end()
                    },
                    error: () => {}
                });
            });

            $(document).on('change', '.billing_address_country[name=country]', function (e){
                let el = $(this);
                let country = el.val();

                $.ajax({
                    url: '{{route('tenant.shop.checkout.state.ajax')}}',
                    type: 'GET',
                    data: {
                        country: country
                    },

                    beforeSend: () => {
                        el.parent().parent().find('.billing_address_state').html('');
                        CustomLoader.start();
                    },
                    success: (data) => {
                        el.parent().parent().find('.billing_address_state').html(data.markup);
                        CustomLoader.end();
                    },
                    error: () => {}
                });
            });

            $(document).on('change', '.shift-another-country, .shift-another-state', function (e){
                let country = $('.shift-another-country :selected').val();
                let state = $('.shift-another-state :selected').val();

                $('.coupon-country').val(country);
                $('.coupon-state').val(state);

                getCountryStateBasedTotal(country, state);
            });

            $(document).on('change', '.billing_address_country, .billing_address_state', function (e){
                let country = $('.billing_address_country :selected').val();
                let state = $('.billing_address_state :selected').val();

                $('.coupon-country').val(country);
                $('.coupon-state').val(state);

                getCountryStateBasedTotal(country, state);
            });

            $(document).on('click', 'input[name=shipping_method]', function (){
                let el = $(this);
                let shipping_method = el.val();
                let total = $('.price-total').attr('data-total');

                $('.shipping-method').val(shipping_method);

                if (total !== undefined)
                {
                    getShippingMethodBasedTotal(shipping_method, $('.coupon-country').val(), $('.coupon-state').val(), total);
                }
            });

            function getShippingMethodBasedTotal(shipping_method ,country, state, total) {
                let checkout_btn = $('.checkout_disable');
                checkout_btn.addClass('proceed_checkout_btn');
                checkout_btn.css({'background': 'var(--main-color-two)', 'border': '2px solid var(--main-color-two)', 'color': '#fff', 'cursor': 'pointer'});

                $.ajax({
                    url: '{{route('tenant.shop.checkout.sync-product-shipping.ajax')}}',
                    type: 'GET',
                    data: {
                        shipping_method: shipping_method,
                        country: country,
                        state: state,
                        total: total
                    },beforeSend: () => {
                        CustomLoader.start();
                    },
                    success: (data) => {
                        if (data.type === 'success')
                        {
                            let currency = '{{site_currency_symbol()}}';
                            $('.price-shipping span').last().html(currency + data.selected_shipping_method.options.cost);
                            $('.price-total span').last().html(currency + data.total);
                            CustomLoader.end();

                            $('.coupon-shipping-method').val(shipping_method);
                        } else {
                            toastr.error(data.msg);
                            checkout_btn.css({'background': '#9d9d9d', 'border': '2px solid #9d9d9d', 'color': '#fff', 'cursor': 'not-allowed'});
                            checkout_btn.removeClass('proceed_checkout_btn');
                            CustomLoader.end();
                        }
                    },
                    error: () => {}
                });
            }

            function getCountryStateBasedTotal(country, state) {
                $.ajax({
                    url: '{{route('tenant.shop.checkout.sync-product-total.ajax')}}',
                    type: 'GET',
                    data: {
                        country: country,
                        state: state
                    },

                    beforeSend: () => {
                        CustomLoader.start();
                    },
                    success: (data) => {
                        $('.shipping_method_wrapper').html(data.sync_price_total_markup);
                        CustomLoader.end();

                        $('.coupon-country').val(country);
                        $('.coupon-state').val(state);
                    },
                    error: () => {}
                });
            }

            $(document).on('click', '.coupon-btn', function (e){
                e.preventDefault();

                let coupon = $('.coupon-code').val();
                let country = $('.coupon-country').val();
                let state = $('.coupon-state').val();
                let shipping = $('.coupon-shipping-method').val();

                let user_coupon = $('.used_coupon');

                $.ajax({
                    url: '{{route('tenant.shop.checkout.sync-product-coupon.ajax')}}',
                    type: 'GET',
                    data: {
                        coupon: coupon,
                        country: country,
                        state: state,
                        shipping_method: shipping
                    },

                    beforeSend: () => {
                        user_coupon.val('');
                        CustomLoader.start();
                    },
                    success: (data) => {
                        console.log(data)
                        {{--if (data.type === 'error')--}}
                        {{--{--}}
                        {{--    CustomSweetAlertTwo.error(data.msg)--}}
                        {{--}--}}

                        {{--//$('.coupon-code').val('');--}}
                        {{--CustomLoader.end()--}}

                        {{--if (data.type == 'success')--}}
                        {{--{--}}
                        {{--    let currency_symbol = '{{site_currency_symbol()}}';--}}
                        {{--    $('.price-total').attr('data-total', data.coupon_amount);--}}
                        {{--    $('.price-total span').text(currency_symbol+data.coupon_amount);--}}
                        {{--    user_coupon.val(coupon);--}}

                        {{--    CustomSweetAlertTwo.success(data.msg)--}}
                        {{--}--}}
                    },
                    error: (error) => {
                        let responseData = error.responseJSON.errors;
                        $.each(responseData, function (index, value){
                            CustomSweetAlertTwo.error(value)
                        });

                        CustomLoader.end();
                    }
                });
            });

            var defaulGateway = $('#site_global_payment_gateway').val();
            $('.payment-gateway-wrapper ul li[data-gateway="' + defaulGateway + '"]').addClass('selected');

            $(document).on('click', '.payment-gateway-wrapper > ul > li', function (e) {
                e.preventDefault();

                let gateway = $(this).data('gateway');

                if (gateway === 'manual_payment_') {
                    $('.manual_transaction_id').removeClass('d-none');
                } else {
                    $('.manual_transaction_id').addClass('d-none');
                    $('.manual_transaction_id input').val('');
                }

                $(this).addClass('selected').siblings().removeClass('selected');
                $('.payment-gateway-wrapper').find('input').val($(this).data('gateway'));
                $('.payment_gateway_passing_clicking_name').val($(this).data('gateway'));

            });

            $(document).on('keyup', '.manual_transaction_id input[name=trasaction_id]', function (e){
                $('input[name=manual_trasaction_id]').val($(this).val());
            });

            $(document).on('click', '.cash-on-delivery, .cash-on-delivery label', function (){
                $('.payment-inlines').toggleClass('d-none');
                $('input[name=manual_trasaction_id]').val('');
                $('.payment_gateway_passing_clicking_name').val('');
                $('.payment-gateway-wrapper ul li').removeClass('selected');

                let cod = $('.cash_on_delivery').val();
                if (cod === '')
                {
                    $('.cash_on_delivery').val('on');
                } else {
                    $('.cash_on_delivery').val('');
                }
            });

            $(document).on('click', '.create-accounts', function (e){
                let need_account = $('.create_accounts_input');

                if(need_account.val() === '')
                {
                    need_account.val('on');
                } else {
                    need_account.val('');
                }

                $('.create-account-wrapper .checkout-form-open').toggleClass('active')
            });

            $(document).on('click', '.proceed_checkout_btn', function (e){
                e.preventDefault();

                let agreed = $('#agree:checked');
                if (agreed.length !== 0)
                {
                    $('form.checkout-form').trigger('submit');
                } else {
                    CustomSweetAlertTwo.error('{{__('You need to agree to our Terms & Conditions to complete the order')}}')
                }
            });

        });
    </script>
@endsection
