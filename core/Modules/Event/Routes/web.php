<?php
/*----------------------------------------------------------------------------------------------------------------------------
|                                                      BACKEND ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use \Modules\Event\Http\Controllers\Tenant\Admin\EventController;
use \Modules\Event\Http\Controllers\Tenant\Admin\EventCategoryController;
use \Modules\Event\Http\Controllers\Tenant\Frontend\FrontendEventController;
use \Modules\Event\Http\Controllers\Tenant\Frontend\EventPaymentLogController;

Route::middleware([
    'web',
    \App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class,
    'auth:admin',
    'tenant_admin_glvar',
    'package_expire',
    'tenantAdminPanelMailVerify',
    'setlang'

])->prefix('admin-home')->group(function(){

    Route::controller(EventController::class)->prefix('event')->name('tenant.')->group(function(){

        Route::get('/', 'index')->name('admin.event');
        Route::get('/new', 'create')->name('admin.event.new');
        Route::post('/new', 'store');
        Route::get('/edit/{id}', 'edit')->name('admin.event.edit');
        Route::post('/update/{id}', 'update')->name('admin.event.update');
        Route::post('/delete/{id}', 'delete')->name('admin.event.delete');
        Route::post('/clone', 'clone')->name('admin.event.clone');
        Route::post('/bulk-action', 'bulk_action')->name('admin.event.bulk.action');
        Route::get('/settings', 'settings')->name('admin.event.settings');
        Route::post('/settings', 'update_settings');

        //payment data
        Route::get('/payment-logs', 'event_payment_logs')->name('admin.event.payment.logs');
        Route::get('/payment-logs-report', 'event_payment_logs_report')->name('admin.event.payment.logs.report');
        Route::post('/payment-log-delete/{id}', 'event_payment_log_delete')->name('admin.event.payment.log.delete');
        Route::post('/payment-log-bulk-action', 'event_payment_log_bulk_action')->name('admin.event.payment.log.bulk.action');

        //EVENT Comments Route
        Route::get('/comments/view/{id}', 'view_comments')->name('admin.event.comments.view');
        Route::post('/comments/delete/all/lang/{id}', 'delete_all_comments')->name('admin.event.comments.delete.all.lang');
        Route::post('/comments/bulk-action', 'bulk_action_comments')->name('admin.event.comments.bulk.action');
        Route::post('/invoice/generate', 'event_invoice')->name('admin.event.invoice.generate');
        Route::post('/payment/accept/{id}', 'event_payment_accept')->name('admin.event.payment.accept');
    });

    //BACKEND EVENT CATEGORY AREA
    Route::controller(EventCategoryController::class)->prefix('event-category')->name('tenant.')->group(function(){
        Route::get('/','index')->name('admin.event.category');
        Route::post('/','store');
        Route::post('/update','update')->name('admin.event.category.update');
        Route::post('/destroy/{id}','destroy')->name('admin.event.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.event.category.bulk.action');
    });

});


Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant_glvar',
    'setlang'
])->group(function () {
    /*----------------------------------------------------------------------------------------------------------------------------
    |                                                      FRONTEND ROUTES (Tenants)
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::middleware('package_expire')->controller(FrontendEventController::class)->prefix('event')->name('tenant.')->group(function () {

        Route::get('/search', 'event_search_page')->name('frontend.event.search.page');
        Route::get('/{slug}', 'event_single')->name('frontend.event.single');
        Route::get('/category/{id}', 'category_wise_event_page')->name('frontend.event.category');
        Route::post('/event/comment/store', 'event_comment_store')->name('frontend.event.comment.store');
        Route::post('/all/comment', 'load_more_comments')->name('frontend.load.event.comment.data');
        Route::get('/booking/payment/{slug}', 'event_payment')->name('frontend.event.payment');
        Route::get('/payment/success/{id}', 'event_payment_success')->name('frontend.event.payment.success');
        Route::get('/ticket/download/{id}', 'event_ticket_download')->name('frontend.event.ticket.download');
        Route::get('/static/payment/cancel', 'event_payment_cancel')->name('frontend.event.payment.cancel');
    });

    //Event Payment
    Route::middleware('package_expire')->controller(EventPaymentLogController::class)->prefix('payment-event')->name('tenant.')->group(function () {
        Route::post('/confirm','event_payment_form')->name('frontend.event.payment.form');
        Route::post('/paypal-ipn', 'paypal_ipn')->name('frontend.event.paypal.ipn');
        Route::post('/paytm-ipn', 'paytm_ipn')->name('frontend.event.paytm.ipn');
        Route::get('/mollie-ipn', 'mollie_ipn')->name('frontend.event.mollie.ipn');
        Route::get('/stripe-ipn', 'stripe_ipn')->name('frontend.event.stripe.ipn');
        Route::post('/razorpay-ipn', 'razorpay_ipn')->name('frontend.event.razorpay.ipn');
        Route::post('/payfast-ipn', 'payfast_ipn')->name('frontend.event.payfast.ipn');
        Route::get('/flutterwave/ipn', 'flutterwave_ipn')->name('frontend.event.flutterwave.ipn');
        Route::get('/paystack-ipn', 'paystack_ipn')->name('frontend.event.paystack.ipn');
        Route::get('/midtrans-ipn', 'midtrans_ipn')->name('frontend.event.midtrans.ipn');
        Route::post('/cashfree-ipn', 'cashfree_ipn')->name('frontend.event.cashfree.ipn');
        Route::get('/instamojo-ipn', 'instamojo_ipn')->name('frontend.event.instamojo.ipn');
        Route::get('/paypal-ipn', 'paypal_ipn')->name('frontend.paypal.event.ipn');
        Route::get('/marcadopago-ipn', 'marcadopago_ipn')->name('frontend.event.marcadopago.ipn');
        Route::get('/squareup-ipn', 'squareup_ipn')->name('frontend.event.squareup.ipn');
        Route::post('/cinetpay-ipn', 'cinetpay_ipn')->name('frontend.event.cinetpay.ipn');
        Route::post('/paytabs-ipn', 'paytabs_ipn')->name('frontend.event.paytabs.ipn');
        Route::post('/billplz-ipn', 'billplz_ipn')->name('frontend.event.billplz.ipn');
        Route::post('/zitopay-ipn', 'zitopay_ipn')->name('frontend.event.zitopay.ipn');
        Route::post('/toyyibpay-ipn', 'toyyibpay_ipn')->name('frontend.event.toyyibpay.ipn');
        Route::post('/pagali-ipn', 'pagali_ipn')->name('frontend.event.pagali.ipn');
        Route::get('/authorizenet-ipn', 'authorizenet_ipn')->name('frontend.event.authorizenet.ipn');
        Route::get('/sitesway-ipn', 'sitesway_ipn')->name('frontend.event.sitesway.ipn');
        Route::post('/kinetic-ipn', 'kinetic_ipn')->name('frontend.event.kinetic.ipn')->excludedMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    });


});
