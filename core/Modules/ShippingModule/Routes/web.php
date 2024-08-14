<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\ShippingModule\Http\Controllers\ShippingMethodController;
use App\Http\Controllers\Shipping\ZoneController;

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
    /*-----------------------------------
        SHIPPING ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'shipping'], function () {
        /*-----------------------------------
            ZONE ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'zone', 'as' => 'admin.shipping.zone.'], function () {
            Route::get('/', 'ZoneController@index')->name('all');
            Route::post('new', 'ZoneController@store')->name('new');
            Route::post('update', 'ZoneController@update')->name('update');
            Route::post('delete/{item}', 'ZoneController@destroy')->name('delete');
            Route::post('bulk-action', 'ZoneController@bulk_action')->name('bulk.action');
        });

        /*-----------------------------------
            METHOD ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'method', 'as' => 'admin.shipping.method.'], function () {
            Route::get('/', 'ShippingMethodController@index')->name('all');
            Route::get('new', 'ShippingMethodController@create')->name('new');
            Route::post('new', 'ShippingMethodController@store');
            Route::get('edit/{item}', 'ShippingMethodController@edit')->name('edit');
            Route::post('update', 'ShippingMethodController@update')->name('update');
            Route::post('delete/{item}', 'ShippingMethodController@destroy')->name('delete');
            Route::post('bulk-action', 'ShippingMethodController@bulk_action')->name('bulk.action');
            Route::post('make-default', 'ShippingMethodController@makeDefault')->name('make.default');
        });
    });
});

