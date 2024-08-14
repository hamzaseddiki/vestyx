<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    \App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class,
    'auth:admin',
    'tenant_admin_glvar',
    'package_expire',
    'tenantAdminPanelMailVerify'
])->prefix('admin-home')->name('tenant.')->group(function () {
        /*-----------------------------------
                    COUPON ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'coupons', 'as' => 'admin.product.coupon.'], function () {
            Route::get('/', 'AdminCouponController@index')->name('all');
            Route::post('new', 'AdminCouponController@store')->name('new');
            Route::post('update', 'AdminCouponController@update')->name('update');
            Route::post('delete/{item}', 'AdminCouponController@destroy')->name('delete');
            Route::post('bulk-action', 'AdminCouponController@bulk_action')->name('bulk.action');
            Route::get('check', 'AdminCouponController@check')->name('check');
            Route::get('get-products', 'AdminCouponController@allProductsAjax')->name('products');
        });
});

