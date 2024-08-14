<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\TaxModule\Http\Controllers\Tenant\Admin\CountryTaxController;
use Modules\TaxModule\Http\Controllers\Tenant\Admin\StateTaxController;

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
    /*----------------------------------------------------------------------------------------------------------------------------
    | BACKEND PRODUCT TAX MANAGE AREA
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'tax', 'as' => 'admin.tax.'], function () {
        /*-----------------------------------
                COUNTRY TAX ROUTES
         ------------------------------------*/
        Route::group(['prefix' => 'country', 'as' => 'country.'], function () {
            Route::controller(CountryTaxController::class)->group(function () {
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            });
        });
        /*-----------------------------------
            STATE TAX ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'state', 'as' => 'state.'], function () {
            Route::controller(StateTaxController::class)->group(function () {
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            });
        });
    });
});

