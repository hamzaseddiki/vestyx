<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\Badge\Http\Controllers\BadgeController;

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
    | BACKEND BADGES MANAGE AREA
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'badge' ,'as' => 'admin.badge.'], function () {
        Route::controller(BadgeController::class)->group(function (){
            Route::get('/', 'index')->name('all');
            Route::post('new', 'store')->name('store');
            Route::post('update/{item}', 'update')->name('update');
            Route::post('delete/{item}', 'destroy')->name('delete');
            Route::post('bulk-action', 'bulk_action_delete')->name('bulk.action.delete');

            Route::prefix('trash')->group(function (){
                Route::get('/', 'trash')->name('trash');
                Route::get('/restore/{id}', 'trash_restore')->name('trash.restore');
                Route::post('/delete/{item}', 'trash_delete')->name('trash.delete');
                Route::post('/bulk-action', 'trash_bulk_action_delete')->name('trash.bulk.action.delete');
            });
        });
    });
});
