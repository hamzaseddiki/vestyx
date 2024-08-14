<?php

use Modules\TwoFactorAuthentication\Entities\LoginSecurity;

Route::group(['prefix'=>'user','middleware'=>['auth','userMailVerify','Google2FA','landlord_glvar','setlang']],function(){

    Route::get('/enable2fa', 'LoginSecurityController@show2faForm')->name('landlord.user.enable2fa');
    Route::post('/enable2fa', 'LoginSecurityController@enable2fa');
    Route::get('/generateSecret', 'LoginSecurityController@generate2faSecret')->name('buyer.generate.2fa.Secret');
});

Route::group(['prefix'=>'seller','middleware'=>['auth','inactiveuser','userMailVerify','setlang','Google2FA']],function(){
    Route::get('/enable2fa', 'LoginSecurityController@show2faForm')->name('seller.enable2fa');
    Route::post('/enable2fa', 'LoginSecurityController@enable2fa');
    Route::get('/generateSecret', 'LoginSecurityController@generate2faSecret')->name('seller.generate.2fa.Secret');
});

Route::group(['middleware' => ['landlord_glvar','setlang','auth']], function () {

    Route::get('user/verify-2fa', 'LoginSecurityController@verify_2fa_code')->name('frontend.verify.2fa.code');
    Route::post('user/verify-2fa', 'LoginSecurityController@verify_secret_code')->name('frontend.verify.2fa.code');
});

Route::post('/disable/2fa', 'LoginSecurityController@disable2fa')->name('disable2fa'); //have to check it work or not
