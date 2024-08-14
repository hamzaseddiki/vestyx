<?php
///*----------------------------------------------------------------------------------------------------------------------------
//|                                                      BACKEND ROUTES
//|----------------------------------------------------------------------------------------------------------------------------*/
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use \Modules\Knowledgebase\Http\Controllers\Tenant\Admin\KnowledgebaseCategoryController;
use \Modules\Knowledgebase\Http\Controllers\Tenant\Admin\KnowledgebaseController;
use \Modules\Knowledgebase\Http\Controllers\Tenant\Frontend\FrontendKnowledgebaseController;

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

    Route::controller(KnowledgebaseController::class)->prefix('knowledgebase')->name('tenant.')->group(function(){
        Route::get('/', 'index')->name('admin.knowledgebase');
        Route::get('/new', 'create')->name('admin.knowledgebase.new');
        Route::post('/new', 'store');
        Route::get('/edit/{id}', 'edit')->name('admin.knowledgebase.edit');
        Route::post('/update/{id}', 'update')->name('admin.knowledgebase.update');
        Route::post('/delete/{id}', 'delete')->name('admin.knowledgebase.delete');
        Route::post('/clone', 'clone')->name('admin.knowledgebase.clone');
        Route::post('/bulk-action', 'bulk_action')->name('admin.knowledgebase.bulk.action');
        Route::get('/settings', 'settings')->name('admin.knowledgebase.settings');
        Route::post('/settings', 'update_settings');
    });

    //BACKEND CATEGORY AREA
    Route::controller(KnowledgebaseCategoryController::class)->prefix('knowledgebase-category')->name('tenant.')->group(function(){
        Route::get('/','index')->name('admin.knowledgebase.category');
        Route::post('/','store');
        Route::post('/update','update')->name('admin.knowledgebase.category.update');
        Route::post('/destroy/{id}','destroy')->name('admin.knowledgebase.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.knowledgebase.category.bulk.action');
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
    Route::middleware('package_expire')->controller(FrontendKnowledgebaseController::class)->prefix('knowledgebase')->name('tenant.')->group(function () {
        Route::get('/search', 'knowledgebase_search_page')->name('frontend.knowledgebase.search.page');
        Route::get('/{slug}', 'knowledgebase_single')->name('frontend.knowledgebase.single');
        Route::get('/category/{id}', 'category_wise_knowledgebase_page')->name('frontend.knowledgebase.category');
    });



});
