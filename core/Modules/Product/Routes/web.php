<?php

use Modules\Product\Http\Controllers\CategoryController;
use Modules\Product\Http\Controllers\ProductController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\Product\Http\Middleware\ProductLimitMiddleware;
use Modules\Product\Http\Controllers\Frontend\FrontendProductController;
use Modules\Product\Http\Controllers\Frontend\CheckoutPaymentController;
use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\OrderManageController;

Route::middleware([
    'web',
    \App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class,
    'auth:admin',
    'tenant_admin_glvar',
    'package_expire',
    'tenantAdminPanelMailVerify',
    'setlang'
])->prefix('admin-home')->name('tenant.')->group(function () {
    /*==============================================
                    PRODUCT MODULE
    ==============================================*/
    Route::prefix('product')->as("admin.product.")->group(function (){
        Route::controller(ProductController::class)->group(function (){
            Route::get("all",'index')->name("all");
            Route::get("create","create")->name("create");
            Route::post("create","store")->middleware(ProductLimitMiddleware::class);
            Route::post("status-update","update_status")->name("update.status");
            Route::get("update/{id}/{aria_name?}", "edit")->name("edit");
            Route::post("update/{id}", "update");
            Route::get("destroy/{id}", "destroy")->name("destroy");
            Route::get("clone/{id}", "clone")->name("clone")->middleware(ProductLimitMiddleware::class);;
            Route::post("bulk/destroy", "bulk_destroy")->name("bulk.destroy");
            Route::get("search","productSearch")->name("search");

            Route::prefix('trash')->name('trash.')->group(function (){
                Route::get('/', 'trash')->name('all');
                Route::get('/restore/{id}', 'restore')->name('restore');
                Route::get('/delete/{id}', 'trash_delete')->name('delete');
                Route::post("/bulk/destroy", "trash_bulk_destroy")->name("bulk.destroy");
                Route::post("/empty", "trash_empty")->name("empty");
            });
        });
    });

    /*==============================================
                    Product Module Category Route
    ==============================================*/
    Route::prefix("product-category")->as("admin.category.")->group(function (){
        Route::controller(CategoryController::class)->group(function (){

            Route::post("category","getCategory")->name("all");
            Route::post("sub-category","getSubCategory")->name("sub-category");
            Route::post("child-category","getChildCategory")->name("child-category");
        });
    });

    /*----------------------------------------------------------------------------------------------------------------------------
    | PRODUCT ORDER MANAGE
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(OrderManageController::class)->prefix('order-manage')->group(function () {
        Route::get('/all', 'all_orders')->name('admin.product.order.manage.all');
        Route::get('/view/{id}', 'view_order')->name('admin.product.order.manage.view');
        Route::get('/pending', 'pending_orders')->name('admin.product.order.manage.pending');
        Route::get('/completed', 'completed_orders')->name('admin.product.order.manage.completed');
        Route::get('/in-progress', 'in_progress_orders')->name('admin.product.order.manage.in.progress');
        Route::post('/change-status', 'change_status')->name('admin.product.order.manage.change.status');
        Route::post('/send-mail', 'send_mail')->name('admin.product.order.manage.send.mail');
        //thank you page
        Route::get('/success-page', 'order_success_payment')->name('admin.product.order.success.page');
        Route::post('/success-page', 'update_order_success_payment');
        //cancel page
        Route::get('/cancel-page', 'order_cancel_payment')->name('admin.product.order.cancel.page');
        Route::post('/cancel-page', 'update_order_cancel_payment');
        Route::get('/order-page', 'index')->name('admin.product.order.page');
        Route::post('/order-page', 'udpate');
        Route::post('/bulk-action', 'bulk_action')->name('admin.product.order.bulk.action');
        Route::post('/reminder', 'order_reminder')->name('admin.product.order.reminder');
        Route::get('/order-report', 'order_report')->name('admin.product.order.report');
        //payment log route
        Route::get('/payment-logs', 'all_payment_logs')->name('admin.payment.logs');
        Route::post('/payment-logs/delete/{id}', 'payment_logs_delete')->name('admin.payment.delete');
        Route::post('/payment-logs/approve/{id}', 'payment_logs_approve')->name('admin.payment.approve');
        Route::post('/payment-logs/bulk-action', 'payment_log_bulk_action')->name('admin.payment.bulk.action');
        Route::get('/payment-logs/report', 'payment_report')->name('admin.payment.report');
        Route::post('/order-user/generate-invoice', 'generate_order_invoice')->name('admin.order.invoice.generate');
        Route::post('/payment/accept/{id}', 'product_payment_accept')->name('admin.product.payment.accept');

        //Order settings route
        Route::match(['get', 'post'] ,'/order/settings', 'order_manage_settings')->name('admin.product.order.settings');
    });



});



//*============================================== =================================================
                                     //product Frontend Routes
//===============================================================================================

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant_glvar',
    'maintenance_mode',
    'setlang'
])->group(function () {


Route::get('/get-campaign-product-end-date', [FrontendProductController::class, 'get_campaign_product_end_date'])->name('tenant.campaign.end.date');
Route::get('/campaign-details/{id}', [FrontendProductController::class, 'campaign_details'])->name('tenant.campaign.details');
Route::get('/search', [FrontendProductController::class, 'topbar_search'])->name('tenant.search.ajax');

Route::prefix('shop')->as('tenant.')->group(function (){
    Route::get('/category-wise-product', [FrontendProductController::class, 'product_by_category_ajax'])->name('category.wise.product');
    Route::get('/search', [FrontendProductController::class, 'shop_page'])->name('shop');
    Route::get('/product/search', [FrontendProductController::class, 'shop_search'])->name('shop.search');
    Route::get('/product/quick-view', [FrontendProductController::class, 'product_quick_view'])->name('shop.quick.view');
    Route::get('/product/{slug}', [FrontendProductController::class, 'product_details'])->name('shop.product.details'); // Product Details


    Route::get('/type/{category_type?}/{slug}', [FrontendProductController::class, 'category_products'])->name('shop.category.products'); // Product Category / Subcategory / ChildCategory

    Route::post('/product/review', [FrontendProductController::class, 'product_review'])->name('shop.product.review'); // Product Review
    Route::get('/product/review/more', [FrontendProductController::class, 'render_reviews'])->name('shop.product.review.more.ajax'); // Product Review Ajax

    Route::post('/product/cart/add', [FrontendProductController::class, 'add_to_cart'])->name('shop.product.add.to.cart.ajax'); // Shop to Add to Cart
    Route::post('/product/wishlist/add', [FrontendProductController::class, 'add_to_wishlist'])->name('shop.product.add.to.wishlist.ajax'); // Shop to Add to Cart
    Route::post('/product/compare/add', [FrontendProductController::class, 'add_to_compare'])->name('shop.product.add.to.compare.ajax'); // Shop to Add to Cart
    Route::get('/cart', [FrontendProductController::class, 'cart_page'])->name('shop.cart');
    Route::get('/wishlist/page', [FrontendProductController::class, 'wishlist_page'])->name('shop.wishlist.page');
    Route::get('/cart/update', [FrontendProductController::class, 'cart_update_ajax'])->name('shop.cart.update.ajax');
    Route::get('/cart/clear', [FrontendProductController::class, 'cart_clear_ajax'])->name('shop.cart.clear.ajax');
    Route::get('/cart/product/delete', [FrontendProductController::class, 'cart_remove_product_ajax'])->name('shop.cart.remove.product.ajax');
    Route::get('/wishlist/product/delete', [FrontendProductController::class, 'wishlist_remove_product_ajax'])->name('shop.wishlist.remove.product.ajax');
    Route::get('/wishlist/product/move', [FrontendProductController::class, 'wishlist_move_product_ajax'])->name('shop.wishlist.move.product.ajax');

    Route::post('/product/buy/add', [FrontendProductController::class, 'buy_now'])->name('shop.product.buy.now.ajax'); // Shop to Add to Cart

    Route::get('/checkout', [CheckoutPaymentController::class, 'checkout_page'])->name('shop.checkout');
    Route::post('/checkout', [CheckoutPaymentController::class, 'checkout'])->name('shop.checkout.final');

    Route::get('/checkout/get-state', [FrontendProductController::class, 'get_state_ajax'])->name('shop.checkout.state.ajax');
    Route::get('/checkout/shipping-tax-data', [FrontendProductController::class, 'sync_product_total'])->name('shop.checkout.sync-product-total.ajax');
    Route::get('/checkout/shipping-method-data', [FrontendProductController::class, 'sync_product_total_wth_shipping_method'])->name('shop.checkout.sync-product-shipping.ajax');

    Route::get('/checkout/coupon', [FrontendProductController::class, 'sync_product_coupon'])->name('shop.checkout.sync-product-coupon.ajax');

    Route::get('/wishlist', [FrontendProductController::class, 'wishlist_product'])->name('shop.wishlist.product');

    Route::get('/compare/items', [FrontendProductController::class, 'compare_product'])->name('shop.compare.product');
    Route::get('/compare', [FrontendProductController::class, 'compare_product_page'])->name('shop.compare.product.page');
    Route::get('/compare/remove', [FrontendProductController::class, 'compare_product_remove'])->name('shop.compare.product.remove');

    Route::get('/quick-viewpage/{slug}', [FrontendProductController::class, 'productQuickViewPage'])->name('products.single-quick-view');

    // Payment IPN
    Route::prefix("payment-product")->as("user.frontend.")->group(function (){
        Route::controller(CheckoutPaymentController::class)->group(function (){
            Route::post('/paytm-ipn', 'paytm_ipn')->name('paytm.ipn');
            Route::get('/mollie-ipn', 'mollie_ipn')->name('mollie.ipn');
            Route::get('/stripe-ipn', 'stripe_ipn')->name('stripe.ipn');
            Route::post('/razorpay-ipn', 'razorpay_ipn')->name('razorpay.ipn');
            Route::post('/payfast-ipn', 'payfast_ipn')->name('payfast.ipn');
            Route::get('/flutterwave/ipn', 'flutterwave_ipn')->name('flutterwave.ipn');
            Route::get('/paystack-ipn', 'paystack_ipn')->name('paystack.ipn');
            Route::get('/midtrans-ipn', 'midtrans_ipn')->name('midtrans.ipn');
            Route::post('/cashfree-ipn', 'cashfree_ipn')->name('cashfree.ipn');
            Route::get('/instamojo-ipn', 'instamojo_ipn')->name('instamojo.ipn');
            Route::get('/paypal-ipn', 'paypal_ipn')->name('paypal.ipn');
            Route::get('/marcadopago-ipn', 'marcadopago_ipn')->name('marcadopago.ipn');

            Route::get('/squareup-ipn', 'squareup_ipn')->name('squareup.ipn');
            Route::post('/cinetpay-ipn', 'cinetpay_ipn')->name('cinetpay.ipn');
            Route::post('/paytabs-ipn', 'paytabs_ipn')->name('paytabs.ipn');
            Route::post('/billplz-ipn', 'billplz_ipn')->name('billplz.ipn');
            Route::post('/zitopay-ipn', 'zitopay_ipn')->name('zitopay.ipn');

            Route::post('/toyyibpay-ipn', 'toyyibpay_ipn')->name('toyyibpay.ipn');
            Route::post('/pagali-ipn', 'pagali_ipn')->name('pagali.ipn');
            Route::get('/authorizenet-ipn', 'authorizenet_ipn')->name('authorizenet.ipn');
            Route::get('/sitesway-ipn', 'sitesway_ipn')->name('sitesway.ipn');
            Route::post('/kinetic-ipn', 'kinetic_ipn')->name('kinetic.ipn')->excludedMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);


            Route::get('/order-success/{id}','order_payment_success')->name('order.payment.success');
            Route::get('/order-cancel/{id}','order_payment_cancel')->name('order.payment.cancel');
            Route::get('/order-cancel-static','order_payment_cancel_static')->name('order.payment.cancel.static');
            Route::get('/order-confirm/{id}','order_confirm')->name('order.confirm');
        });
    });




Route::group(['prefix' => 'product', 'as' => 'tenant.products.', 'middleware' =>[
        'web',
        InitializeTenancyByDomain::class,
        PreventAccessFromCentralDomains::class,
        'tenant_glvar',
        'maintenance_mode',
    ]], function () {



        /**--------------------------------
         *          CART ROUTES
         * ---------------------------------*/
        Route::group(['prefix' => 'cart'], function () {
            Route::get('/all', 'FrontendProductController@cartPage')->name('cart');
            /**--------------------------------
             *          CART AJAX ROUTES
             * ---------------------------------*/
            Route::group(['prefix' => 'ajax'], function () {
//                Route::get('details', 'ProductCartController@cartStatus')->name('cart.status.ajax');
//                Route::post('remove', 'ProductCartController@removeCartItem')->name('cart.ajax.remove');
//                Route::post('clear', 'ProductCartController@clearCart')->name('cart.ajax.clear');
//                Route::get('cart-info', 'ProductCartController@getCartInfoAjax')->name('cart.info.ajax');
//                Route::post('add-to-cart', 'ProductCartController@addToCartAjax')->name('add.to.cart.ajax');
//                Route::post('update', 'ProductCartController@updateCart')->name('cart.update.ajax');
//                Route::post('coupon', 'ProductCartController@applyCouponAjax')->name('cart.apply.coupon');
            });
        });

        /**--------------------------------
         *      COMPARE PRODUCT ROUTES
         * ---------------------------------*/
//        Route::group(['prefix' => 'compare'], function () {
//            Route::get('all', 'FrontendProductController@productsComparePage')->name('compare');
//            Route::post('add', 'ProductCompareController@addToCompare')->name('add.to.compare');
//            Route::post('remove', 'ProductCompareController@removeFromCompare')->name('compare.ajax.remove');
//            Route::post('clear', 'ProductCompareController@clearCompare')->name('ajax.compare.update');
//        });
    });
});



});
