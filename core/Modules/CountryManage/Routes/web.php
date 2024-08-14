<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\CountryManage\Http\Controllers\Tenant\Admin\CountryManageController;
use Modules\CountryManage\Http\Controllers\Tenant\Admin\StateController;

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
    | BACKEND COUNTRY MANAGE AREA
    |----------------------------------------------------------------------------------------------------------------------------*/
    // tenant.admin.state.by.country
    Route::group(['as' => 'admin.'], function () {
        /*-----------------------------------
            COUNTRY ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'country', "as" => "country."], function () {
            Route::controller(CountryManageController::class)->group(function () {
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            });
        });

        /*-----------------------------------
            STATE ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'state', 'as' => 'state.'], function () {
            Route::controller(StateController::class)->group(function () {
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
                Route::get('country-state', 'getStateByCountry')->name('by.country');
                Route::post('mutliple-country-state', 'getMultipleStateByCountry')->name('by.multiple.country');
            });
        });
    });
});

