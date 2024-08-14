<?php

use Illuminate\Support\Facades\Route;

// backend routes
// Buyer = User

Route::group(['prefix' => 'admin-home/wallet','as'=>'landlord.admin.wallet.', 'middleware' => ['adminglobalVariable','auth:admin', 'setlang']], function () {
    Route::get( '/lists', 'Backend\WalletController@wallet_lists')->name('lists');
    Route::post( '/status/{id}', 'Backend\WalletController@change_status')->name('status');
    Route::get( '/history/records', 'Backend\WalletController@wallet_history')->name('history');
    Route::post( '/history/records/status/{id}', 'Backend\WalletController@wallet_history_status')->name('history.status');
    Route::get( '/history/settings', 'Backend\WalletController@wallet_settings')->name('settings');
    Route::post( '/history/settings', 'Backend\WalletController@wallet_settings_update');
});


//buyer routes
Route::group(['as'=>'landlord.user.','prefix'=>'landlord', 'middleware'=> ['landlord_glvar','maintenance_mode', 'setlang']],function(){
    Route::controller(\Modules\Wallet\Http\Controllers\Frontend\BuyerWalletController::class)->group(function () {
        Route::get('/wallet-history', 'wallet_history')->name('wallet.history');
        Route::post('/wallet/deposit', 'deposit')->name('wallet.deposit');
        Route::get('wallet/deposit-cancel-static','deposit_payment_cancel_static')->name('wallet.deposit.payment.cancel.static');
        Route::get('wallet/deposit-success','deposit_payment_success')->name('wallet.deposit.payment.success');

        Route::get('wallet/settings','wallet_settings')->name('wallet.settings');
        Route::post('wallet/settings','wallet_settings_update');
    });
});

Route::group(['prefix' => 'wallet'],function (){
    //wallet payment routes
    Route::get('/paypal-ipn','Frontend\BuyerWalletPaymentController@paypal_ipn_for_wallet')->name('buyer.paypal.ipn.wallet');
    Route::post('/paytm-ipn','Frontend\BuyerWalletPaymentController@paytm_ipn_for_wallet')->name('buyer.paytm.ipn.wallet');
    Route::get('/paystack-ipn','Frontend\BuyerWalletPaymentController@paystack_ipn_for_wallet')->name('buyer.paystack.ipn.wallet');
    Route::get('/mollie/ipn','Frontend\BuyerWalletPaymentController@mollie_ipn_for_wallet')->name('buyer.mollie.ipn.wallet');
    Route::get('/stripe/ipn','Frontend\BuyerWalletPaymentController@stripe_ipn_for_wallet')->name('buyer.stripe.ipn.wallet');
    Route::post('/razorpay-ipn','Frontend\BuyerWalletPaymentController@razorpay_ipn_for_wallet')->name('buyer.razorpay.ipn.wallet');
    Route::get('/flutterwave/ipn','Frontend\BuyerWalletPaymentController@flutterwave_ipn_for_wallet')->name('buyer.flutterwave.ipn.wallet');
    Route::get('/midtrans-ipn','Frontend\BuyerWalletPaymentController@midtrans_ipn_for_wallet')->name('buyer.midtrans.ipn.wallet');
    Route::post('/payfast-ipn','Frontend\BuyerWalletPaymentController@payfast_ipn_for_wallet')->name('buyer.payfast.ipn.wallet');
    Route::post('/cashfree-ipn','Frontend\BuyerWalletPaymentController@cashfree_ipn_for_wallet')->name('buyer.cashfree.ipn.wallet');
    Route::get('/instamojo-ipn','Frontend\BuyerWalletPaymentController@instamojo_ipn_for_wallet')->name('buyer.instamojo.ipn.wallet');
    Route::get('/marcadopago-ipn','Frontend\BuyerWalletPaymentController@marcadopago_ipn_for_wallet')->name('buyer.marcadopago.ipn.wallet');
    Route::get('/squareup-ipn','Frontend\BuyerWalletPaymentController@squareup_ipn_for_wallet' )->name('buyer.squareup.ipn.wallet');
    Route::post('/cinetpay-ipn', 'Frontend\BuyerWalletPaymentController@cinetpay_ipn_for_wallet' )->name('buyer.cinetpay.ipn.wallet');
    Route::post('/paytabs-ipn','Frontend\BuyerWalletPaymentController@paytabs_ipn_for_wallet' )->name('buyer.paytabs.ipn.wallet');
    Route::post('/billplz-ipn','Frontend\BuyerWalletPaymentController@billplz_ipn_for_wallet' )->name('buyer.billplz.ipn.wallet');
    Route::post('/zitopay-ipn','Frontend\BuyerWalletPaymentController@zitopay_ipn_for_wallet' )->name('buyer.zitopay.ipn.wallet');
    Route::post('/toyyibpay-ipn','Frontend\BuyerWalletPaymentController@toyyibpay_ipn_for_wallet' )->name('buyer.toyyibpay.ipn.wallet');
    Route::post('/kinetic-ipn','Frontend\BuyerWalletPaymentController@kinetic_ipn_for_wallet' )->name('buyer.kinetic.ipn.wallet')->excludedMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

});
