<?php

/*
|--------------------------------------------------------------------------
| Web Routes - PRODUCT ATTRIBUTE
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\Attributes\Http\Controllers\CategoryController;
use Modules\Attributes\Http\Controllers\SubCategoryController;
use Modules\Attributes\Http\Controllers\ChildCategoryController;
use Modules\Attributes\Http\Controllers\UnitController;
use Modules\Attributes\Http\Controllers\TagController;
use Modules\Attributes\Http\Controllers\ColorController;
use Modules\Attributes\Http\Controllers\SizeController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\Attributes\Http\Controllers\BrandController;
use Modules\Attributes\Http\Controllers\DeliveryOptionController;
use Modules\Attributes\Http\Controllers\AttributesController;

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
    | BACKEND PRODUCT ATTRIBUTE MANAGE AREA
    |----------------------------------------------------------------------------------------------------------------------------*/

    Route::group(['as' => 'admin.'], function (){
        /*-----------------------------------
            PRODUCT CATEGORY  ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'product-category', 'as' => 'product.category.'], function () {
            Route::controller(CategoryController::class)->group(function () {
                Route::get('/category', 'index')->name('all');
                Route::post('/new', 'store')->name('new');
                Route::post('/update', 'update')->name('update');
                Route::post('/delete/{item}', 'destroy')->name('delete');
                Route::post('/bulk-action', 'bulk_action')->name('bulk.action');

                Route::prefix('trash')->name('trash.')->group(function () {
                    Route::get('/', 'trash')->name('all');
                    Route::get('/restore/{id}', 'trash_restore')->name('restore');
                    Route::get('/delete/{id}', 'trash_delete')->name('delete');
                    Route::post('/bulk/delete', 'trash_bulk_delete')->name('delete.bulk');
                });
            });
        });

        /*-----------------------------------
            PRODUCT SUB-CATEGORY  ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'sub-categories', 'as' => 'product.subcategory.'], function () {
            Route::controller(SubCategoryController::class)->group(function (){
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
                Route::get('of-category/{id}', 'getSubcategoriesOfCategory')->name('of.category');
                Route::get('of-category/select/{id}', 'getSubcategoriesForSelect')->name('of.category');

                Route::prefix('trash')->name('trash.')->group(function () {
                    Route::get('/', 'trash')->name('all');
                    Route::get('/restore/{id}', 'trash_restore')->name('restore');
                    Route::get('/delete/{id}', 'trash_delete')->name('delete');
                    Route::post('/bulk/delete', 'trash_bulk_delete')->name('delete.bulk');
                });
            });
        });

        /*-----------------------------------
            PRODUCT CHILD-CATEGORY  ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'child-categories', 'as' => 'product.child-category.'], function () {
            Route::controller(ChildCategoryController::class)->group(function () {
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
                Route::get('of-category/{id}', 'getSubcategoriesOfCategory')->name('of.category');

                Route::prefix('trash')->name('trash.')->group(function (){
                    Route::get('/', 'trash')->name('all');
                    Route::get('/restore/{id}', 'trash_restore')->name('restore');
                    Route::get('/delete/{id}', 'trash_delete')->name('delete');
                    Route::post('/bulk/delete', 'trash_bulk_delete')->name('delete.bulk');
                });
            });
        });
        /*-----------------------------------
            TAG ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'tags', 'as' => 'product.tag.'], function () {
            Route::controller(TagController::class)->group(function (){
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
                Route::get('check', 'check')->name('check');
                Route::get('get-tags', 'getTagsAjax')->name('get.ajax');
            });
        });

        /*-----------------------------------
            PRODUCTS UNIT ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'units', 'as' => 'product.units.'], function () {
            Route::controller(UnitController::class)->group(function (){
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('store');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            });
        });

        /*-----------------------------------
            PRODUCTS COLOR ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'colors', 'as' => 'product.colors.'], function () {
            Route::controller(ColorController::class)->group(function (){
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            });
        });

        /*-----------------------------------
            PRODUCTS COLOR ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'sizes', 'as' => 'product.sizes.'], function () {
            Route::controller(SizeController::class)->group(function (){
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            });
        });

        /*-----------------------------------
            PRODUCTS Delivery Option ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'delivery-manage', 'as' => 'product.delivery.option.'], function () {
            Route::controller(DeliveryOptionController::class)->group(function (){
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('store');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');

                Route::prefix('trash')->name('trash.')->group(function (){
                    Route::get('/', 'trash_all')->name('all');
                    Route::get('/restore/{id}', 'trash_restore')->name('restore');
                    Route::post('/delete/{id}', 'trash_delete')->name('delete');
                    Route::post('/bulk-action/delete', 'trash_bulk_action')->name('bulk.action');
                });
            });
        });

        /*-----------------------------------
            Brands ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'brand-manage', 'as' => 'product.brand.manage.'], function () {
            Route::controller(BrandController::class)->group(function (){
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('store');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            });
        });



        /*-----------------------------------------------------------------------------------------
              Product inventory variant route
        -------------------------------------------------------------------------------------------*/
        Route::group(['prefix' => 'attributes', 'as' => 'products.attributes.'], function () {
            Route::controller(AttributesController::class)->group(function (){
                Route::get('/', 'index')->name('all');
                Route::get('/new', 'create')->name('store');
                Route::post('/new', 'store');
                Route::get('/edit/{item}', 'edit')->name('edit');
                Route::post('/update', 'update')->name('update');
                Route::post('/delete/{item}', 'destroy')->name('delete');
                Route::post('/bulk-action', 'bulk_action')->name('bulk.action');
                Route::post('/details', 'get_details')->name('details');
                Route::post('/by-lang', 'get_all_variant_by_lang')->name('admin.products.variant.by.lang');
            });
        });
    });
});
