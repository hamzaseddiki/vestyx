<?php
/*----------------------------------------------------------------------------------------------------------------------------
|                                                      BACKEND ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use \Modules\Appointment\Http\Controllers\Tenant\Admin\AppointmentDaysController;
use \Modules\Appointment\Http\Controllers\Tenant\Admin\AppointmentScheduleController;
use \Modules\Appointment\Http\Controllers\Tenant\Admin\AppointmentDayTypeController;
use \Modules\Appointment\Http\Controllers\Tenant\Admin\SubAppointmentController;
use \Modules\Appointment\Http\Controllers\Tenant\Admin\AppointmentController;
use \Modules\Appointment\Http\Controllers\Tenant\Admin\AppointmentCategoryController;
use \Modules\Appointment\Http\Controllers\Tenant\Admin\AppointmentSubCategoryController;
use \Modules\Appointment\Http\Controllers\Tenant\Frontend\AppointmentFrontendController;
use \Modules\Appointment\Http\Controllers\Tenant\Frontend\SubAppointmentFrontendController;
use \Modules\Appointment\Http\Controllers\Tenant\Frontend\AppointmentPaymentLogController;
use \Modules\Appointment\Http\Controllers\Tenant\Admin\Payment\AppointmentOrderManageController;


Route::middleware([
    'web',
    \App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class,
    'auth:admin',
    'tenant_admin_glvar',
    'package_expire',
    'tenantAdminPanelMailVerify',
    'setlang'

])->prefix('admin-home')->group(function()
{

    //=========================  BACKEND APPOINTMENT DAY TYPES  ==========================
    Route::controller(AppointmentDayTypeController::class)->prefix('appointment-day-types')->name('tenant.')->group(function(){
        Route::get('/','index')->name('admin.appointment.day.types');
        Route::post('/','store');
        Route::post('/update','update')->name('admin.appointment.day.types.update');
        Route::post('/destroy/{id}','destroy')->name('admin.appointment.day.types.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.appointment.day.types.bulk.action');
    });

    //=========================  BACKEND APPOINTMENT DAYS  ==========================
        Route::controller(AppointmentDaysController::class)->prefix('appointment-days')->name('tenant.')->group(function(){
            Route::get('/','index')->name('admin.appointment.days');
            Route::post('/','store');
            Route::post('/update','update')->name('admin.appointment.days.update');
            Route::post('/destroy/{id}','destroy')->name('admin.appointment.days.delete');
            Route::post('/bulk-action', 'bulk_action')->name('admin.appointment.days.bulk.action');
        });


    //=========================  BACKEND APPOINTMENT SCHEDULE  ==========================
    Route::controller(AppointmentScheduleController::class)->prefix('appointment-schedule')->name('tenant.')->group(function(){
        Route::get('/','index')->name('admin.appointment.schedule');
        Route::post('/','store');
        Route::post('/update','update')->name('admin.appointment.schedule.update');
        Route::post('/destroy/{id}','destroy')->name('admin.appointment.schedule.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.appointment.schedule.bulk.action');
    });


   Route::controller(SubAppointmentController::class)->prefix('sub-appointments')->name('tenant.')->group(function(){
        Route::get('/', 'index')->name('admin.sub.appointment');
        Route::get('/new', 'create')->name('admin.sub.appointment.new');
        Route::post('/new', 'store');
        Route::get('/edit/{id}', 'edit')->name('admin.sub.appointment.edit');
        Route::post('/update/{id}', 'update')->name('admin.sub.appointment.update');
        Route::post('/delete/{id}', 'delete')->name('admin.sub.appointment.delete');
        Route::post('/clone', 'clone')->name('admin.sub.appointment.clone');
        Route::post('/bulk-action', 'bulk_action')->name('admin.sub.appointment.bulk.action');
       //Appointment Comments Route
       Route::get('/comments/view/{id}', 'view_comments')->name('admin.sub.appointment.comments.view');
       Route::post('/comments/delete/all/lang/{id}', 'delete_all_comments')->name('admin.sub.appointment.comments.delete.all.lang');
       Route::post('/comments/bulk-action', 'bulk_action_comments')->name('admin.sub.appointment.comments.bulk.action');
    });


    //=========================  BACKEND APPOINTMENT CATEGORY  ==========================
    Route::controller(AppointmentCategoryController::class)->prefix('appointment-category')->name('tenant.')->group(function(){
        Route::get('/','index')->name('admin.appointment.category');
        Route::post('/','store');
        Route::post('/update','update')->name('admin.appointment.category.update');
        Route::post('/destroy/{id}','destroy')->name('admin.appointment.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.appointment.category.bulk.action');
    });

    //=========================  BACKEND APPOINTMENT SUB CATEGORY  ==========================
    Route::controller(AppointmentSubCategoryController::class)->prefix('appointment-subcategory')->name('tenant.')->group(function(){
        Route::get('/','index')->name('admin.appointment.sub.category');
        Route::post('/','store');
        Route::post('/update','update')->name('admin.appointment.sub.category.update');
        Route::post('/destroy/{id}','destroy')->name('admin.appointment.sub.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.appointment.sub.category.bulk.action');
    });

    Route::controller(AppointmentController::class)->prefix('appointments')->name('tenant.')->group(function(){

        Route::get('/', 'index')->name('admin.appointment');
        Route::get('/new', 'create')->name('admin.appointment.new');
        Route::post('/new', 'store');
        Route::get('/edit/{id}', 'edit')->name('admin.appointment.edit');
        Route::post('/update/{id}', 'update')->name('admin.appointment.update');
        Route::post('/delete/{id}', 'delete')->name('admin.appointment.delete');
        Route::post('/clone', 'clone')->name('admin.appointment.clone');
        Route::post('/bulk-action', 'bulk_action')->name('admin.appointment.bulk.action');
        Route::get('/subcategory-via-ajax', 'sub_category_via_ajax')->name('admin.appointment.sub.category.via.ajax');
        Route::get('/settings', 'settings')->name('admin.appointment.settings');
        Route::post('/settings', 'update_settings');


        //Appointment Comments Route
        Route::get('/comments/view/{id}', 'view_comments')->name('admin.appointment.comments.view');
        Route::post('/comments/delete/all/lang/{id}', 'delete_all_comments')->name('admin.appointment.comments.delete.all.lang');
        Route::post('/comments/bulk-action', 'bulk_action_comments')->name('admin.appointment.comments.bulk.action');
    });


      //Payment Data
        Route::controller(AppointmentOrderManageController::class)->prefix('appointment-order')->name('tenant.')->group(function(){
            Route::get('/payment-complete-logs', 'appointment_complete_payment_logs')->name('admin.appointment.complete.payment.logs');
            Route::get('/payment-pending-logs', 'appointment_pending_payment_logs')->name('admin.appointment.pending.payment.logs');
            Route::get('/payment-logs-report', 'appointment_payment_logs_report')->name('admin.appointment.payment.logs.report');
            Route::post('/payment-log-delete/{id}', 'appointment_payment_log_delete')->name('admin.appointment.payment.log.delete');
            Route::post('/payment-log-bulk-action', 'appointment_payment_log_bulk_action')->name('admin.appointment.payment.log.bulk.action');
            Route::post('/invoice/generate', 'appointment_invoice')->name('admin.appointment.invoice.generate');
            Route::post('/payment/accept/{id}', 'appointment_payment_accept')->name('admin.appointment.payment.accept');
        });
     //Payment Data
});


Route::middleware([
    'web',
    \App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class,
    'tenant_glvar',
    'setlang'
])->group(function () {

    /*----------------------------------------------------------------------------------------------------------------------------
    |                                                      FRONTEND ROUTES (Tenants)
    |----------------------------------------------------------------------------------------------------------------------------*/

    Route::middleware('package_expire')->controller(AppointmentFrontendController::class)->prefix('appointment')->name('tenant.')->group(function () {
        Route::get('/get-schedule-via-time-date-ajax', 'get_schedule_via_time_data')->name('frontend.appointment.schedule.via.time.data.ajax');
        Route::get('/search', 'appointment_search_page')->name('frontend.appointment.search');
        Route::get('/{slug}', 'appointment_single')->name('frontend.appointment.single');
        Route::get('/category/{id?}', 'category_wise_appointment_page')->name('frontend.appointment.category');
        Route::post('/comment/store', 'appointment_comment_store')->name('frontend.appointment.comment.store');
        Route::post('/all/comment', 'load_more_comments')->name('frontend.load.appointment.comment.data');
        Route::get('/order-page/{slug}', 'appointment_order_page')->name('frontend.appointment.order.page');
        Route::get('/payment/test-page', 'payment_page')->name('frontend.appointment.payment.page');
        Route::get('/payment/success/{id}', 'appointment_payment_success')->name('frontend.appointment.payment.success');
        Route::get('/static/payment/cancel', 'appointment_payment_cancel')->name('frontend.appointment.payment.cancel');
    });

    Route::middleware('package_expire')->controller(SubAppointmentFrontendController::class)->prefix('sub-appointment')->name('tenant.')->group(function () {
        Route::get('/search', 'sub_appointment_search_page')->name('frontend.sub.appointment.search');
        Route::get('/{slug}', 'sub_appointment_single')->name('frontend.sub.appointment.single');
        Route::post('/comment/store', 'sub_appointment_comment_store')->name('frontend.sub.appointment.comment.store');
        Route::post('/all/comment', 'load_more_comments')->name('frontend.load.sub.appointment.comment.data');
    });

//    // Appointment Payment
    Route::middleware('package_expire')->controller(AppointmentPaymentLogController::class)->prefix('appointment-payment')->name('tenant.')->group(function () {
        Route::post('/store', 'appointment_store')->name('frontend.appointment.payment.store');
        Route::post('/paypal-ipn', 'paypal_ipn')->name('frontend.appointment.paypal.ipn');
        Route::post('/paytm-ipn', 'paytm_ipn')->name('frontend.appointment.paytm.ipn');
        Route::get('/mollie-ipn', 'mollie_ipn')->name('frontend.appointment.mollie.ipn');
        Route::get('/stripe-ipn', 'stripe_ipn')->name('frontend.appointment.stripe.ipn');
        Route::post('/razorpay-ipn', 'razorpay_ipn')->name('frontend.appointment.razorpay.ipn');
        Route::post('/payfast-ipn', 'payfast_ipn')->name('frontend.appointment.payfast.ipn');
        Route::get('/flutterwave/ipn', 'flutterwave_ipn')->name('frontend.appointment.flutterwave.ipn');
        Route::get('/paystack-ipn', 'paystack_ipn')->name('frontend.appointment.paystack.ipn');
        Route::get('/midtrans-ipn', 'midtrans_ipn')->name('frontend.appointment.midtrans.ipn');
        Route::post('/cashfree-ipn', 'cashfree_ipn')->name('frontend.appointment.cashfree.ipn');
        Route::get('/instamojo-ipn', 'instamojo_ipn')->name('frontend.appointment.instamojo.ipn');
        Route::get('/paypal-ipn', 'paypal_ipn')->name('frontend.paypal.appointment.ipn');
        Route::get('/marcadopago-ipn', 'marcadopago_ipn')->name('frontend.appointment.marcadopago.ipn');
        Route::get('/squareup-ipn', 'squareup_ipn')->name('frontend.appointment.squareup.ipn');
        Route::post('/cinetpay-ipn', 'cinetpay_ipn')->name('frontend.appointment.cinetpay.ipn');
        Route::post('/paytabs-ipn', 'paytabs_ipn')->name('frontend.appointment.paytabs.ipn');
        Route::post('/billplz-ipn', 'billplz_ipn')->name('frontend.appointment.billplz.ipn');
        Route::post('/zitopay-ipn', 'zitopay_ipn')->name('frontend.appointment.zitopay.ipn');
        Route::post('/toyyibpay-ipn', 'toyyibpay_ipn')->name('frontend.appointment.toyyibpay.ipn');
        Route::post('/pagali-ipn', 'pagali_ipn')->name('frontend.appointment.pagali.ipn');
        Route::get('/authorizenet-ipn', 'authorizenet_ipn')->name('frontend.appointment.authorizenet.ipn');
        Route::get('/sitesway-ipn', 'sitesway_ipn')->name('frontend.appointment.sitesway.ipn');
        Route::post('/kinetic-ipn', 'kinetic_ipn')->name('frontend.appointment.kinetic.ipn')->excludedMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);




    });
});




