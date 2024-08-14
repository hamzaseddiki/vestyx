@extends(route_prefix().'frontend.frontend-page-master')

@php
    $page_title = $wishlist ? "Wishlist" : "Cart";
@endphp

@section('title')
    {{__($page_title)}} {{__('Page')}}
@endsection

@section('page-title')
    {{__($page_title )}} {{__('Page')}}
@endsection

@section("style")
    <style>
        .table-list-content .custom--table tbody tr td:last-child {
            height: 150px;
            padding-right: 20px;
        }
    </style>
@endsection

@section('content')

    <div class="cart-main-wrapper">
        @if($cart_data->count())
            <!-- Cart Area Starts -->
            <div class="cart-area padding-top-75 padding-bottom-100">
                <div class="container container-one">
                    <div class="row">
                        @include('product::frontend.shop.cart.partials.cart_main_contents')
                    </div>
                </div>
            </div>
            <!-- Cart Area end -->
        @else
            <!-- 404 Area Starts -->
            @include('product::frontend.shop.cart.cart_empty')
            <!-- 404 Area end -->
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        (function ($) {
            'use strict'

            function load_topbar_cart_nad_wishlist()
            {
                $('#track-icon-list').load(location.href + " #track-icon-list");
                $('.track-icon-list').load(location.href + " .track-icon-list");
            }

            $(document).on('change', '.quantity-input', function (e) {
                let el = $(this);
                let product_qty = el.val();
                let product_unique_id = el.closest('[data-product-id]').data('product-id');
                let product_variant_id = el.closest('[data-variant-id]').data('product-variant-id');

                getSubtotal(product_unique_id, product_qty, product_variant_id)
            });

            /* ========================================
                        Product Quantity JS
            ========================================*/

            $(document).on('click', '.plus', function () {
                let selectedInput = $(this).prev('.quantity-input');

                if (selectedInput.val()) {
                    selectedInput[0].stepUp(1);

                    let el = $(this);
                    let product_qty = el.parent().find('.quantity-input').val();
                    let product_unique_id = el.closest('[data-product-id]').data('product-id');
                    let product_variant_id = el.closest('[data-variant-id]').data('product-variant-id');

                    getSubtotal(product_unique_id, product_qty, product_variant_id)
                }
            });

            $(document).on('click', '.substract', function () {
                let selectedInput = $(this).next('.quantity-input');
                if (selectedInput.val() > 1) {
                    selectedInput[0].stepDown(1);

                    let el = $(this);
                    let product_qty = el.parent().find('.quantity-input').val();
                    let product_unique_id = el.closest('[data-product-id]').data('product-id');
                    let product_variant_id = el.closest('[data-variant-id]').data('product-variant-id');

                    getSubtotal(product_unique_id, product_qty, product_variant_id)
                }
            });

            $(document).on('click', '.clear-cart-btn', function (){

                CustomLoader.start();

                setTimeout(() => {
                    $(location).attr('href', '{{route('tenant.shop.cart.clear.ajax')}}');
                }, 300)

                CustomLoader.end();
            });

            $(document).on('click', '.ff-jost .close-table-cart', function (e){
                let el = $(this);
                let product_hash_id = el.parent().data('product_hash_id');

                $.ajax({
                    url: '{{route('tenant.shop.cart.remove.product.ajax')}}',
                    type: 'GET',
                    data: {
                        'product_hash_id': product_hash_id,
                    },
                    beforeSend: function (){
                        CustomLoader.start();
                    },
                    success: function (data){
                        if (data.msg)
                        {

                            CustomSweetAlertTwo.success(data.msg)
                            SohanCustom.load_topbar_cart_nad_wishlist();

                            if (data.empty_cart !== '')
                            {
                                $('.cart-main-wrapper').html(data.empty_cart).hide();
                                $('.cart-main-wrapper').fadeIn();
                            }

                            $('.coupon-contents').parent().load(location.href + " .coupon-contents");

                            $('.track-icon-list').load(location.href + " .track-icon-list");
                            $('.custom--table.table-border.radius-10').parent().load(location.href + " .custom--table.table-border.radius-10");
                        }

                       CustomLoader.end();
                    },
                    error: function (data){
                        CustomSweetAlertTwo.error(data.msg)
                    }
                })
            });

            $(document).on('click', '.ff-jost .close-table-wishlist', function (e){
                let el = $(this);
                let product_hash_id = el.parent().data('product_hash_id');

                $.ajax({
                    url: '{{route('tenant.shop.wishlist.remove.product.ajax')}}',
                    type: 'GET',
                    data: {
                        'product_hash_id': product_hash_id,
                    },
                    beforeSend: function (){
                        CustomLoader.start();
                    },
                    success: function (data){
                        if (data.msg)
                        {
                            SohanCustom.load_topbar_cart_nad_wishlist();
                            CustomSweetAlertTwo.success(data.msg)

                            if (data.empty_cart !== '')
                            {
                                $('.cart-main-wrapper').html(data.empty_cart).hide();
                                $('.cart-main-wrapper').fadeIn();
                            }


                            $('.track-icon-list').load(location.href + " .track-icon-list");
                            $('.custom--table.table-border.radius-10').parent().load(location.href + " .custom--table.table-border.radius-10");
                        }

                        CustomLoader.end();
                    },
                    error: function (data){

                    }
                })
            });

            $(document).on('click', '.ff-jost .move-to-wishlist', function (e){
                let el = $(this);
                let product_hash_id = el.parent().data('product_hash_id');

                $.ajax({
                    url: '{{route('tenant.shop.wishlist.move.product.ajax')}}',
                    type: 'GET',
                    data: {
                        'product_hash_id': product_hash_id,
                    },
                    beforeSend: function (){
                        CustomLoader.start();
                    },
                    success: function (data){
                        if (data.msg) {


                            CustomSweetAlertTwo.success(data.msg)
                            if (data.empty_cart !== '') {
                                $('.cart-main-wrapper').html(data.empty_cart).hide();
                                $('.cart-main-wrapper').fadeIn();
                            }
                            SohanCustom.load_topbar_cart_nad_wishlist();

                            $('.custom--table.table-border.radius-10').parent().load(location.href + " .custom--table.table-border.radius-10");
                        }

                        CustomLoader.end();
                    },
                    error: function (data){

                    }
                })
            });

            function getSubtotal(productId, qty, variantId)
            {
                let product_id = productId;
                let quantity = qty;
                let variant_id = variantId;
                let route = '{{route('tenant.shop.cart.update.ajax')}}';

                sendAjaxRequest(product_id, quantity, variant_id, route, 'GET');
            }

            function sendAjaxRequest(productId, qty , variant_id,url, type)
            {
                $.ajax({
                    url: url,
                    type: type,
                    data: {
                        'product_id': productId,
                        'quantity': qty,
                        'variant_id': variant_id
                    },
                    beforeSend: function (){
                      CustomLoader.start();
                    },
                    success: function (data){

                        if (data.type === 'success')
                        {
                            CustomSweetAlertTwo.success(data.msg)

                            $('#cart_tbody').html(data.markup);

                            //$('.coupon-contents').html(data.cart_price_markup);
                        }
                        else if(data.quantity_msg)
                        {
                            CustomSweetAlertTwo.warning(data.quantity_msg)
                        }

                       CustomLoader.end();
                    },
                    error: function (data){
                      CustomLoader.end();
                      CustomSweetAlertTwo.error(data.error_msg)
                    }
                })
            }
        })(jQuery)
    </script>
@endsection
