<?php

use App\Http\Controllers\Landlord\Frontend\PaymentLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['landlord_glvar','maintenance_mode'])->group(function (){
    Auth::routes(['register' => false]);
});

/* ---------------------------------
    landlord frontend login routes
----------------------------------- */
Route::middleware(['landlord_glvar','maintenance_mode','setlang'])->controller(\App\Http\Controllers\Landlord\Frontend\LandlordFrontendController::class)->group(function (){
    Route::get('/', 'homepage')->name('landlord.homepage');
    Route::post('/subdomain-check',  'subdomain_check')->name('landlord.subdomain.check');
    Route::get('/coupon-check',  'coupon_check')->name('landlord.coupon.ajax.check');
    Route::get('/verify-email','verify_user_email')->name('tenant.email.verify');
    Route::post('/verify-email','check_verify_user_email');
    Route::get('/resend-verify-email','resend_verify_user_email')->name('tenant.email.verify.resend');
    Route::post('store-login','ajax_login')->name('landlord.ajax.login');
    Route::get('/logout-from-landlord','logout_tenant_from_landlord')->name('tenant.admin.logout.from.landlord.home');
    Route::get('/subscribe-page/{token}','subscribe_page')->name('landlord.subscriber.verify');
    Route::get('/un-subscribe-page/{id}','unsubscribe_page')->name('landlord.newsletter.unsubscribe');
});


/*----------------------------------------------------------------------------------------------------------------------------
|                                                Landlord Blog routes
|----------------------------------------------------------------------------------------------------------------------------*/
Route::middleware(['landlord_glvar','maintenance_mode','setlang'])->controller(\App\Http\Controllers\Landlord\Frontend\BlogController::class)->prefix('blog')->name('landlord.')->group(function () {
    Route::get('/search/data', 'blog_search_page')->name('frontend.blog.search');
    Route::get('/{slug}', 'blog_single')->name('frontend.blog.single');
    Route::get('/category/{id}/{any?}', 'category_wise_blog_page')->name('frontend.blog.category');
    Route::get('/tags/{any?}', 'tags_wise_blog_page')->name('frontend.blog.tags.page');
    Route::get('/get/tags', 'get_tags_by_ajax')->name('frontend.get.taags.by.ajax');
    Route::post('/blog/comment/store', 'blog_comment_store')->name('frontend.blog.comment.store');
    Route::post('blog/all/comment', 'load_more_comments')->name('frontend.load.blog.comment.data');
});


//token-wise-login
Route::get('/token-wise-login/{token}', [\App\Http\Controllers\Landlord\Frontend\LandlordFrontendController::class,'loginUsingToken'])->name('landlord.user.login.with.token');


/* -----------------------------
    landlord admin login routes
------------------------------ */
Route::middleware('setlang')->controller(\App\Http\Controllers\Landlord\Admin\Auth\AdminLoginController::class)->prefix('admin')->group(function (){
    Route::get('/','login_form')->name('landlord.admin.login');
    Route::post('/','login_admin');
    Route::post('/logout','logout_admin')->name('landlord.admin.logout');

    //Forget and reset landlord pass
    Route::get('/login/forget-password','showUserForgetPasswordForm')->name('landlord.forget.password');
    Route::get('/login/reset-password/{user}/{token}','showUserResetPasswordForm')->name('landlord.reset.password');
    Route::post('/login/reset-password','UserResetPassword')->name('landlord.reset.password.change');
    Route::post('/login/forget-password','sendUserForgetPasswordMail');
});


Route::controller(\App\Http\Controllers\Landlord\Frontend\FrontendFormController::class)->prefix('landlord')->group(function () {
    Route::post('submit-custom-form', 'custom_form_builder_message')->name('landlord.frontend.form.builder.custom.submit');
});

Route::prefix('user-home')->middleware(['auth:web','maintenance_mode','userMailVerify','setlang'])->controller(\App\Http\Controllers\Tenant\Admin\TenantDashboardController::class)->group(function (){
    Route::get('/','redirect_to_tenant_admin_panel')->name('tenant.home');
});



require_once __DIR__ .'/admin.php';

Route::middleware(['maintenance_mode','landlord_glvar'])->controller(PaymentLogController::class)->name('landlord.')->group(function () {
    Route::post('/paytm-ipn', 'paytm_ipn')->name('frontend.paytm.ipn');
    Route::get('/mollie-ipn', 'mollie_ipn')->name('frontend.mollie.ipn');
    Route::get('/stripe-ipn', 'stripe_ipn')->name('frontend.stripe.ipn');
    Route::post('/razorpay-ipn', 'razorpay_ipn')->name('frontend.razorpay.ipn');
    Route::post('/payfast-ipn', 'payfast_ipn')->name('frontend.payfast.ipn');
    Route::get('/flutterwave/ipn', 'flutterwave_ipn')->name('frontend.flutterwave.ipn');
    Route::get('/paystack-ipn', 'paystack_ipn')->name('frontend.paystack.ipn');
    Route::get('/midtrans-ipn', 'midtrans_ipn')->name('frontend.midtrans.ipn');
    Route::post('/cashfree-ipn', 'cashfree_ipn')->name('frontend.cashfree.ipn');
    Route::get('/instamojo-ipn', 'instamojo_ipn')->name('frontend.instamojo.ipn');
    Route::get('/paypal-ipn', 'paypal_ipn')->name('frontend.paypal.ipn');
    Route::get('/marcadopago-ipn', 'marcadopago_ipn')->name('frontend.marcadopago.ipn');
    Route::get('/squareup-ipn', 'squareup_ipn')->name('frontend.squareup.ipn');
    Route::post('/cinetpay-ipn', 'cinetpay_ipn')->name('frontend.cinetpay.ipn');
    Route::post('/paytabs-ipn', 'paytabs_ipn')->name('frontend.paytabs.ipn');
    Route::post('/billplz-ipn', 'billplz_ipn')->name('frontend.billplz.ipn');
    Route::post('/zitopay-ipn', 'zitopay_ipn')->name('frontend.zitopay.ipn');
    Route::post('/toyyibpay-ipn', 'toyyibpay_ipn')->name('frontend.toyyibpay.ipn');
    Route::post('/pagali-ipn', 'pagali_ipn')->name('frontend.pagali.ipn');
    Route::get('/authorizenet-ipn', 'authorizenet_ipn')->name('frontend.authorizenet.ipn');
    Route::get('/sitesway-ipn', 'sitesway_ipn')->name('frontend.sitesway.ipn');
    Route::post('/kinetic-ipn', 'kinetic_ipn')->name('frontend.kinetic.ipn');
    Route::post('/order-confirm','order_payment_form')->name('frontend.order.payment.form');
});


//LANDLORD HOME PAGE FRONTEND TENANT LOGIN - REGISTRATION
Route::middleware(['landlord_glvar','maintenance_mode','setlang','Google2FA','setlang'])->controller(\App\Http\Controllers\Landlord\Frontend\LandlordFrontendController::class)->name('landlord.')->group(function () {
    Route::get('/login', 'showTenantLoginForm')->name('user.login');
    Route::post('store-login','ajax_login')->name('user.ajax.login');
    Route::get('/register','showTenantRegistrationForm')->name('user.register');
    Route::post('/register-store','tenant_user_create')->name('user.register.store');
    Route::get('/logout','tenant_logout')->name('user.logout');
    Route::get('/login/forget-password','showUserForgetPasswordForm')->name('user.forget.password');
    Route::get('/login/reset-password/{user}/{token}','showUserResetPasswordForm')->name('user.reset.password');
    Route::post('/login/reset-password','UserResetPassword')->name('user.reset.password.change');
    Route::post('/login/forget-password','sendUserForgetPasswordMail');
    Route::get('/user-logout','user_logout')->name('frontend.user.logout');

    Route::get('/verify-email','verify_user_email')->name('user.email.verify');
    Route::post('/verify-email','check_verify_user_email');
    Route::get('/resend-verify-email','resend_verify_user_email')->name('user.email.verify.resend');

    //Order
    Route::get('/plan-order/{id}','plan_order')->name('frontend.plan.order');
    //payment status route
    Route::get('/order-success/{id}','order_payment_success')->name('frontend.order.payment.success');
    Route::get('/order-cancel/{id}','order_payment_cancel')->name('frontend.order.payment.cancel');
    Route::get('/order-cancel-static','order_payment_cancel_static')->name('frontend.order.payment.cancel.static');
    Route::get('/order-confirm/{id}','order_confirm')->name('frontend.order.confirm');

    // Trial Account
    Route::post('/user/trial/account', 'user_trial_account')->name('frontend.trial.account');
});


/*------------------------------
    SOCIAL LOGIN CALLBACK
------------------------------*/
Route::controller(\App\Http\Controllers\Landlord\Frontend\SocialLoginController::class)->name('landlord.')->group(function () {
    Route::group(['prefix' => 'facebook'], function () {
        Route::get('callback', 'facebook_callback')->name('facebook.callback');
        Route::get('redirect', 'facebook_redirect')->name('login.facebook.redirect');
    });
    Route::group(['prefix' => 'google'], function () {
        Route::get('callback', 'google_callback')->name('google.callback');
        Route::get('redirect', 'google_redirect')->name('login.google.redirect');
    });
});

// LANDLORD HOME PAGE Tenant Dashboard Routes
Route::controller(\App\Http\Controllers\Landlord\Frontend\UserDashboardController::class)->middleware(['landlord_glvar','maintenance_mode','userMailVerify','setlang'])->name('landlord.')->group(function(){
    Route::get('/user-home', 'user_index')->name('user.home');
    Route::get('/user/download/file/{id}', 'download_file')->name('user.dashboard.download.file');
    Route::get('/user/change-password', 'change_password')->name('user.home.change.password');
    Route::get('/user/edit-profile', 'edit_profile')->name('user.home.edit.profile');
    Route::post('/user/profile-update', 'user_profile_update')->name('user.profile.update');
    Route::post('/user/password-change', 'user_password_change')->name('user.password.change');
    Route::get('/user/support-tickets', 'support_tickets')->name('user.home.support.tickets');
    Route::get('support-ticket/view/{id}', 'support_ticket_view')->name('user.dashboard.support.ticket.view');
    Route::post('support-ticket/priority-change', 'support_ticket_priority_change')->name('user.dashboard.support.ticket.priority.change');
    Route::post('support-ticket/status-change', 'support_ticket_status_change')->name('user.dashboard.support.ticket.status.change');
    Route::post('support-ticket/message', 'support_ticket_message')->name('user.dashboard.support.ticket.message');
    Route::get('/package-orders', 'package_orders')->name('user.dashboard.package.order');
    Route::get('/custom-domain', 'custom_domain')->name('user.dashboard.custom.domain');
    Route::post('/custom-domain', 'submit_custom_domain');
    Route::post('/package-order/cancel/{id}', 'package_order_cancel')->name('user.dashboard.package.order.cancel');
    Route::post('/package-user/generate-invoice', 'generate_package_invoice')->name('frontend.package.invoice.generate');
    Route::post('/package/renew-process', 'package_renew_process')->name('user.package.renew.process');
    Route::get('/get-themes-via-ajax','get_theme_and_payment_gateway_via_ajax')->name('user.quick.website.theme.via.ajax');
    Route::get('/payment-log-history/{tenant}','payment_log_history')->name('user.dashboard.payment.log.history');
});

//User Support Ticket Routes
Route::controller(\App\Http\Controllers\Landlord\Frontend\SupportTicketController::class)->middleware(['landlord_glvar','setlang'])->name('landlord.')->group(function(){
    Route::get('support-tickets', 'page')->name('frontend.support.ticket');
    Route::post('support-tickets/new', 'store')->name('frontend.support.ticket.store');
});


//Visitor Newsletter Routes
Route::controller(\App\Http\Controllers\Landlord\Frontend\LandlordFrontendController::class)->middleware('landlord_glvar')->name('landlord.')->group(function(){
    Route::post('newsletter/new', 'newsletter_store')->name('frontend.newsletter.store.ajax');
});


//single page route
Route::middleware(['landlord_glvar','maintenance_mode','setlang'])->controller(\App\Http\Controllers\Landlord\Frontend\LandlordFrontendController::class)->name('landlord.')->group(function () {
    //payment page route
    Route::get('/plan-order/{id}','plan_order')->name('frontend.plan.order');
    Route::get('/order-success/{id}','order_payment_success')->name('frontend.order.payment.success');
    Route::get('/order-cancel/{id}','order_payment_cancel')->name('frontend.order.payment.cancel');
    Route::get('/order-cancel-static','order_payment_cancel_static')->name('frontend.order.payment.cancel.static');
    Route::get('/view-plan/{id}/{trial?}','view_plan')->name('frontend.plan.view');
    Route::get('/order-confirm/{id}','order_confirm')->name('frontend.order.confirm');
    Route::get('/lang-change','lang_change')->name('langchange');
    Route::get('/{page:slug}', 'dynamic_single_page')->name('dynamic.page');
});


Route::get("assets/theme/screenshot/{theme}", function ($theme){
    $themeData = renderPrimaryThemeScreenshot($theme);
    $image_name = last(explode('/',$themeData));

    if(file_exists(str_replace('/assets','/screenshot', theme_assets($image_name, $theme)))){
        return response()->file(str_replace('/assets','/screenshot', theme_assets($image_name, $theme)));
    }

    return abort(404);
})->name("theme.primary.screenshot");

Route::get("assets/payment-gateway/screenshot/{moduleName}/{gatewayName}", function ($moduleName, $gatewayName){
    $image_name = getPaymentGatewayImagePath($gatewayName);
    $module_path = module_path($moduleName).'/assets/payment-gateway-image/'.$image_name;

    if(file_exists($module_path)){
        return response()->file($module_path);
    }

    return abort(404);
})->name("payment.gateway.logo");
