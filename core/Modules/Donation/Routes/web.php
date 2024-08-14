<?php
/*----------------------------------------------------------------------------------------------------------------------------
|                                                      BACKEND ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use \Modules\Donation\Http\Controllers\Tenant\Admin\DonationActivityCategoryController;
use \Modules\Donation\Http\Controllers\Tenant\Admin\DonationActivityController;
use \Modules\Donation\Http\Controllers\Tenant\Frontend\DonationPaymentLogController;
use \Modules\Donation\Http\Controllers\Tenant\Frontend\ActivitiesController;

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

    Route::controller(\Modules\Donation\Http\Controllers\Tenant\Admin\DonationController::class)->prefix('donation')->name('tenant.')->group(function(){
        Route::get('/', 'index')->name('admin.donation');
        Route::get('/new', 'create')->name('admin.donation.new');
        Route::post('/new', 'store');
        Route::get('/edit/{id}', 'edit')->name('admin.donation.edit');
        Route::post('/update/{id}', 'update')->name('admin.donation.update');
        Route::post('/delete/{id}', 'delete')->name('admin.donation.delete');
        Route::post('/clone', 'clone')->name('admin.donation.clone');
        Route::post('/bulk-action', 'bulk_action')->name('admin.donation.bulk.action');
        Route::get('/settings', 'settings')->name('admin.donation.settings');
        Route::post('/settings', 'update_settings');

        //payment data
        Route::get('/payment-logs', 'donation_payment_logs')->name('admin.donation.payment.logs');
        Route::get('/payment-logs-report', 'donation_payment_logs_report')->name('admin.donation.payment.logs.report');
        Route::post('/payment-log-delete/{id}', 'donation_payment_log_delete')->name('admin.donation.payment.log.delete');
        Route::post('/payment-log-bulk-action', 'donation_payment_log_bulk_action')->name('admin.donation.payment.log.bulk.action');

        //Donation Comments Route
        Route::get('/comments/view/{id}', 'view_comments')->name('admin.donation.comments.view');
        Route::post('/comments/delete/all/lang/{id}', 'delete_all_comments')->name('admin.donation.comments.delete.all.lang');
        Route::post('/comments/bulk-action', 'bulk_action_comments')->name('admin.donation.comments.bulk.action');
        Route::post('/invoice/generate', 'donation_invoice')->name('admin.donation.invoice.generate');
        Route::post('/payment/accept/{id}', 'donation_payment_accept')->name('admin.donation.payment.accept');
    });

    //BACKEND DONATION CATEGORY AREA
    Route::controller(\Modules\Donation\Http\Controllers\Tenant\Admin\DonationCategoryController::class)->prefix('donation-category')->name('tenant.')->group(function(){
        Route::get('/','index')->name('admin.donation.category');
        Route::post('/','store');
        Route::post('/update','update')->name('admin.donation.category.update');
        Route::post('/destroy/{id}','destroy')->name('admin.donation.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.donation.category.bulk.action');
    });


    //BACKEND DONATION ACTIVITY AREA
    Route::controller(DonationActivityController::class)->prefix('donation-activity')->name('tenant.')->group(function(){
        Route::get('/', 'index')->name('admin.donation.activity');
        Route::get('/new', 'create')->name('admin.donation.activity.new');
        Route::post('/new', 'store');
        Route::get('/edit/{id}', 'edit')->name('admin.donation.activity.edit');
        Route::post('/update/{id}', 'update')->name('admin.donation.activity.update');
        Route::post('/delete/{id}', 'delete')->name('admin.donation.activity.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.donation.activity.bulk.action');
    });


    //BACKEND DONATION ACTIVITY CATEGORY AREA
    Route::controller(DonationActivityCategoryController::class)->prefix('donation-activity-category')->name('tenant.')->group(function(){
        Route::get('/','index')->name('admin.donation.activity.category');
        Route::post('/','store');
        Route::post('/update','update')->name('admin.donation.activity.category.update');
        Route::post('/destroy/{id}','destroy')->name('admin.donation.activity.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.donation.activity.category.bulk.action');
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
    Route::middleware('package_expire')->controller(\Modules\Donation\Http\Controllers\Tenant\Frontend\DonationController::class)->prefix('donation')->name('tenant.')->group(function () {

        Route::get('/search/data', 'donation_search_page')->name('frontend.donation.search');
        Route::get('/{slug}', 'donation_single')->name('frontend.donation.single');
        Route::get('/category/{id}/{any?}', 'category_wise_donation_page')->name('frontend.donation.category');
        Route::get('/tags/{any}', 'tags_wise_donation_page')->name('frontend.donation.tags.page');
        Route::get('donation/autocomplete/search/tag/page', 'auto_complete_search_tag_donations');
        Route::get('/get/tags', 'get_tags_by_ajax')->name('frontend.get.taags.by.ajax');
        Route::get('/get/donation/by/ajax', 'get_donation_by_ajax')->name('frontend.get.donations.by.ajax');
        Route::post('/donation/comment/store', 'donation_comment_store')->name('frontend.donation.comment.store');
        Route::post('donation/all/comment', 'load_more_comments')->name('frontend.load.donation.comment.data');
        Route::get('/payment/{id}', 'donation_payment')->name('frontend.donation.payment');
        Route::get('/payment/success/{id}', 'donation_payment_success')->name('frontend.donation.payment.success');
        Route::get('/static/payment/cancel', 'donation_payment_cancel')->name('frontend.donation.payment.cancel');
    });

    //Donation Payment
    Route::middleware('package_expire')->controller(\Modules\Donation\Http\Controllers\Tenant\Frontend\DonationPaymentLogController::class)->prefix('payment-donation')->name('tenant.')->group(function () {
        Route::post('/confirm','donation_payment_form')->name('frontend.donation.payment.form');
        Route::post('/paypal-ipn', 'paypal_ipn')->name('frontend.donation.paypal.ipn');
        Route::post('/paytm-ipn', 'paytm_ipn')->name('frontend.donation.paytm.ipn');
        Route::get('/mollie-ipn', 'mollie_ipn')->name('frontend.donation.mollie.ipn');
        Route::get('/stripe-ipn', 'stripe_ipn')->name('frontend.donation.stripe.ipn');
        Route::post('/razorpay-ipn', 'razorpay_ipn')->name('frontend.donation.razorpay.ipn');
        Route::post('/payfast-ipn', 'payfast_ipn')->name('frontend.donation.payfast.ipn');
        Route::get('/flutterwave/ipn', 'flutterwave_ipn')->name('frontend.donation.flutterwave.ipn');
        Route::get('/paystack-ipn', 'paystack_ipn')->name('frontend.donation.paystack.ipn');
        Route::get('/midtrans-ipn', 'midtrans_ipn')->name('frontend.donation.midtrans.ipn');
        Route::post('/cashfree-ipn', 'cashfree_ipn')->name('frontend.donation.cashfree.ipn');
        Route::get('/instamojo-ipn', 'instamojo_ipn')->name('frontend.donation.instamojo.ipn');
        Route::get('/paypal-ipn', 'paypal_ipn')->name('frontend.paypal.donation.ipn');
        Route::get('/marcadopago-ipn', 'marcadopago_ipn')->name('frontend.donation.marcadopago.ipn');
        Route::get('/squareup-ipn', 'squareup_ipn')->name('frontend.donation.squareup.ipn');
        Route::post('/cinetpay-ipn', 'cinetpay_ipn')->name('frontend.donation.cinetpay.ipn');
        Route::post('/paytabs-ipn', 'paytabs_ipn')->name('frontend.donation.paytabs.ipn');
        Route::post('/billplz-ipn', 'billplz_ipn')->name('frontend.donation.billplz.ipn');
        Route::post('/zitopay-ipn', 'zitopay_ipn')->name('frontend.donation.zitopay.ipn');
        Route::post('/toyyibpay-ipn', 'toyyibpay_ipn')->name('frontend.donation.toyyibpay.ipn');
        Route::post('/pagali-ipn', 'pagali_ipn')->name('frontend.donation.pagali.ipn');
        Route::get('/authorizenet-ipn', 'authorizenet_ipn')->name('frontend.donation.authorizenet.ipn');
        Route::get('/sitesway-ipn', 'sitesway_ipn')->name('frontend.donation.sitesway.ipn');
        Route::post('/kinetic-ipn', 'kinetic_ipn')->name('frontend.donation.kinetic.ipn')->excludedMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    });

    Route::middleware('package_expire')->controller(ActivitiesController::class)->prefix('activities')->name('tenant.')->group(function () {
        //Donation Activities
        Route::get('/{slug}', 'donation_activities_single')->name('frontend.donation.activities.single');
        Route::get('/category/{id}/{any?}', 'category_wise_donation_activities_page')->name('frontend.donation.activities.category');
    });

});
