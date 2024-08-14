<?php
/*----------------------------------------------------------------------------------------------------------------------------
|                                                      BACKEND ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use \Modules\Job\Http\Controllers\Tenant\Admin\JobController;
use \Modules\Job\Http\Controllers\Tenant\Admin\JobCategoryController;
use \Modules\Job\Http\Controllers\Tenant\Frontend\FrontendJobController;
use \Modules\Job\Http\Controllers\Tenant\Frontend\JobPaymentLogController;


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

    Route::controller(JobController::class)->prefix('job')->name('tenant.')->group(function(){
        Route::get('/', 'index')->name('admin.job');
        Route::get('/new', 'create')->name('admin.job.new');
        Route::post('/new', 'store');
        Route::get('/edit/{id}', 'edit')->name('admin.job.edit');
        Route::post('/update/{id}', 'update')->name('admin.job.update');
        Route::post('/delete/{id}', 'delete')->name('admin.job.delete');
        Route::post('/clone', 'clone')->name('admin.job.clone');
        Route::post('/bulk-action', 'bulk_action')->name('admin.job.bulk.action');
        Route::get('/settings', 'settings')->name('admin.job.settings');
        Route::post('/settings', 'update_settings');

        //payment data
        Route::get('/paid-payment-logs', 'job_paid_payment_logs')->name('admin.job.paid.payment.logs');
        Route::get('/unpaid-payment-logs', 'job_unpaid_payment_logs')->name('admin.job.unpaid.payment.logs');
        Route::get('/payment-logs-report', 'job_payment_logs_report')->name('admin.job.payment.logs.report');
        Route::post('/payment-log-delete/{id}', 'job_payment_log_delete')->name('admin.job.payment.log.delete');
        Route::post('/payment-log-bulk-action', 'job_payment_log_bulk_action')->name('admin.job.payment.log.bulk.action');
        Route::post('/invoice/generate', 'job_invoice')->name('admin.job.invoice.generate');
        Route::post('/payment/accept/{id}', 'job_payment_accept')->name('admin.job.payment.accept');
    });

    //BACKEND JOB CATEGORY AREA
    Route::controller(JobCategoryController::class)->prefix('job-category')->name('tenant.')->group(function(){
        Route::get('/','index')->name('admin.job.category');
        Route::post('/','store');
        Route::post('/update','update')->name('admin.job.category.update');
        Route::post('/destroy/{id}','destroy')->name('admin.job.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.job.category.bulk.action');
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
    Route::middleware('package_expire')->controller(FrontendJobController::class)->prefix('job')->name('tenant.')->group(function () {

        Route::get('/search', 'job_search_page')->name('frontend.job.search.page');
        Route::get('/{slug}', 'job_single')->name('frontend.job.single');
        Route::get('/category/{id}', 'category_wise_job_page')->name('frontend.job.category');
        Route::get('/booking/payment/{slug}', 'job_payment')->name('frontend.job.payment');
        Route::get('/payment/success/{id}', 'job_payment_success')->name('frontend.job.payment.success');
        Route::get('/static/payment/cancel', 'job_payment_cancel')->name('frontend.job.payment.cancel');
    });

    //Event Payment
    Route::middleware('package_expire')->controller(JobPaymentLogController::class)->prefix('payment-job')->name('tenant.')->group(function () {
        Route::post('/confirm','job_payment_store')->name('frontend.job.payment.form');
        Route::post('/paypal-ipn', 'paypal_ipn')->name('frontend.job.paypal.ipn');
        Route::post('/paytm-ipn', 'paytm_ipn')->name('frontend.job.paytm.ipn');
        Route::get('/mollie-ipn', 'mollie_ipn')->name('frontend.job.mollie.ipn');
        Route::get('/stripe-ipn', 'stripe_ipn')->name('frontend.job.stripe.ipn');
        Route::post('/razorpay-ipn', 'razorpay_ipn')->name('frontend.job.razorpay.ipn');
        Route::post('/payfast-ipn', 'payfast_ipn')->name('frontend.job.payfast.ipn');
        Route::get('/flutterwave/ipn', 'flutterwave_ipn')->name('frontend.job.flutterwave.ipn');
        Route::get('/paystack-ipn', 'paystack_ipn')->name('frontend.job.paystack.ipn');
        Route::get('/midtrans-ipn', 'midtrans_ipn')->name('frontend.job.midtrans.ipn');
        Route::post('/cashfree-ipn', 'cashfree_ipn')->name('frontend.job.cashfree.ipn');
        Route::get('/instamojo-ipn', 'instamojo_ipn')->name('frontend.job.instamojo.ipn');
        Route::get('/paypal-ipn', 'paypal_ipn')->name('frontend.paypal.job.ipn');
        Route::get('/marcadopago-ipn', 'marcadopago_ipn')->name('frontend.job.marcadopago.ipn');
        Route::get('/squareup-ipn', 'squareup_ipn')->name('frontend.job.squareup.ipn');
        Route::post('/cinetpay-ipn', 'cinetpay_ipn')->name('frontend.job.cinetpay.ipn');
        Route::post('/paytabs-ipn', 'paytabs_ipn')->name('frontend.job.paytabs.ipn');
        Route::post('/billplz-ipn', 'billplz_ipn')->name('frontend.job.billplz.ipn');
        Route::post('/zitopay-ipn', 'zitopay_ipn')->name('frontend.job.zitopay.ipn');
        Route::post('/toyyibpay-ipn', 'toyyibpay_ipn')->name('frontend.job.toyyibpay.ipn');
        Route::post('/pagali-ipn', 'pagali_ipn')->name('frontend.job.pagali.ipn');
        Route::get('/authorizenet-ipn', 'authorizenet_ipn')->name('frontend.job.authorizenet.ipn');
        Route::get('/sitesway-ipn', 'sitesway_ipn')->name('frontend.job.sitesway.ipn');
        Route::post('/kinetic-ipn', 'kinetic_ipn')->name('frontend.job.kinetic.ipn')->excludedMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    });


});
