<?php

/*----------------------------------------------------------------------------------------------------------------------------
|                                                      BACKEND ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use \Modules\Portfolio\Http\Controllers\Tenant\Admin\PorfolioCategoryController;
use \Modules\Portfolio\Http\Controllers\Tenant\Admin\PortfolioController;


Route::middleware([
    'web',
    \App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class,
    'auth:admin',
    'tenant_admin_glvar',
    'package_expire',
    'tenantAdminPanelMailVerify',
    'setlang'

])->prefix('admin-home')->group(function(){

    Route::controller(PortfolioController::class)->prefix('portfolio')->name('tenant.')->group(function(){
        Route::get('/', 'index')->name('admin.portfolio');
        Route::get('/new', 'create')->name('admin.portfolio.new');
        Route::post('/new', 'store');
        Route::get('/edit/{id}', 'edit')->name('admin.portfolio.edit');
        Route::post('/update/{id}', 'update')->name('admin.portfolio.update');
        Route::post('/delete/{id}', 'delete')->name('admin.portfolio.delete');
        Route::post('/clone', 'clone')->name('admin.portfolio.clone');
        Route::post('/bulk-action', 'bulk_action')->name('admin.portfolio.bulk.action');
    });

    //BACKEND DONATION CATEGORY AREA
    Route::controller(PorfolioCategoryController::class)->prefix('portfolio-category')->name('tenant.')->group(function(){
        Route::get('/','index')->name('admin.portfolio.category');
        Route::post('/','store');
        Route::post('/update','update')->name('admin.portfolio.category.update');
        Route::post('/destroy/{id}','destroy')->name('admin.portfolio.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.portfolio.category.bulk.action');
    });

});


Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant_glvar',
    'setlang'
])->group(function () {


    /*----------------------------------------------------------------------------------------------------------------------------
    |                                                      FRONTEND ROUTES (Tenants)
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\Modules\Portfolio\Http\Controllers\Tenant\Frontend\PortfolioController::class)->prefix('portfolio')->name('tenant.')->group(function () {
        Route::get('/{slug}', 'portfolio_details')->name('frontend.portfolio.single');
        Route::get('/category/{id}/{slug?}', 'category_wise_portfolio')->name('frontend.portfolio.category');
    });

});


