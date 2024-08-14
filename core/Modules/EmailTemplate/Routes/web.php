<?php

use \Modules\EmailTemplate\Http\Controllers\Landlord\EmailTemplateController;

/*------------------------------------------
     LANDLORD ADMIN ROUTES
-------------------------------------------- */

Route::group(['middleware' => ['auth:admin','adminglobalVariable','setlang'],'prefix' => 'admin-home'],function (){

    Route::controller(EmailTemplateController::class)->namespace('Landlord')->name('landlord.')->prefix('email-template')->group(function (){
        Route::get('/', 'all')->name('admin.email.template.all');

        /*-------------------------------------------
         ADMIN EMAIL VERIFY ROUTES
        ---------------------------------------------*/
        Route::get('/admin-email-verify', 'admin_email_verify')->name('admin.email.template.admin.email.verify');
        Route::post('/admin-email-verify', 'update_admin_email_verify');


        /*-------------------------------------------
         USER EMAIL VERIFY ROUTES
        ---------------------------------------------*/
        Route::get('/user-email-verify', 'user_email_verify')->name('admin.email.template.user.email.verify');
        Route::post('/user-email-verify', 'update_user_email_verify');

        /*-------------------------------------------
            ADMIN PASSWORD RESET ROUTES
        ---------------------------------------------*/
        Route::get('/admin-password-reset', 'admin_password_reset')->name('admin.email.template.admin.password.reset');
        Route::post('/admin-password-reset', 'update_admin_password_reset');

        /*-------------------------------------------
          USER PASSWORD RESET ROUTES
        ---------------------------------------------*/
        Route::get('/user-password-reset', 'user_password_reset')->name('admin.email.template.user.password.reset');
        Route::post('/user-password-reset', 'update_user_password_reset');


        /*-------------------------------------------
          SUBSCRIPTION ROUTES
        ---------------------------------------------*/
        //To Admin
        Route::get('/subscription-order-mail-admin', 'subscription_order_mail_admin')->name('admin.subscription.order.mail.admin');
        Route::post('/subscription-order-mail-admin', 'update_subscription_order_mail_admin');

        //To User
        Route::get('/subscription-order-mail-user', 'subscription_order_mail_user')->name('admin.subscription.order.mail.user');
        Route::post('/subscription-order-mail-user', 'update_subscription_order_mail_user');

       //Credential Mail To User
        Route::get('/subscription-order-credential-mail-user', 'subscription_order_credential_mail_user')->name('admin.subscription.order.credential.mail.user');
        Route::post('/subscription-order-credential-mail-user', 'update_subscription_order_credential_mail_user');

        //Subscription Trial with Credential Mail To User
        Route::get('/subscription-order-trial-mail-user', 'subscription_order_trial_mail_user')->name('admin.subscription.order.trial.mail.user');
        Route::post('/subscription-order-trial-mail-user', 'update_subscription_order_trial_mail_user');

        //Manual Payment Approved Mail To User
        Route::get('/subscription-order-manual-payment-approved-mail', 'subscription_order_manual_payment_approved_mail')->name('admin.subscription.order.manual.payment.approved.mail');
        Route::post('/subscription-order-manual-payment-approved-mail', 'update_subscription_order_manual_payment_approved_mail');

    });
});


