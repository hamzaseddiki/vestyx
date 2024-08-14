<?php
/*----------------------------------------------------------------------------------------------------------------------------
|                                                      BACKEND ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


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

    Route::controller(\Modules\Blog\Http\Controllers\Tenant\Admin\BlogController::class)->prefix('blog')->name('tenant.')->group(function () {
        Route::get('/', 'index')->name('admin.blog');
        Route::get('/new', 'new_blog')->name('admin.blog.new');
        Route::post('/new', 'store_new_blog');
        Route::get('/edit/{id}', 'edit_blog')->name('admin.blog.edit');
        Route::post('/update/{id}', 'update_blog')->name('admin.blog.update');
        Route::post('/clone', 'clone_blog')->name('admin.blog.clone');
        Route::post('/delete/all/lang/{id}', 'delete_blog_all_lang')->name('admin.blog.delete.all.lang');
        Route::post('/bulk-action', 'bulk_action_blog')->name('admin.blog.bulk.action');

        //Blog Comments Route
        Route::get('/comments/view/{id}', 'view_comments')->name('admin.blog.comments.view');
        Route::post('/comments/delete/all/lang/{id}', 'delete_all_comments')->name('admin.blog.comments.delete.all.lang');
        Route::post('/comments/bulk-action', 'bulk_action_comments')->name('admin.blog.comments.bulk.action');

        // Page Settings
        Route::get('/settings', 'blog_settings')->name('admin.blog.settings');
        Route::post('/settings', 'update_blog_settings');

    });

    //BACKEND BLOG CATEGORY AREA
    Route::controller(\Modules\Blog\Http\Controllers\Tenant\Admin\BlogCategoryController::class)->prefix('blog-category')->name('tenant.')->middleware(['adminglobalVariable','auth:admin'])->group(function(){
        Route::get('/','index')->name('admin.blog.category');
        Route::post('/store','new_category')->name('admin.blog.category.store');
        Route::post('/update','update_category')->name('admin.blog.category.update');
        Route::post('/delete/all/lang/{id}','delete_category_all_lang')->name('admin.blog.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.blog.category.bulk.action');
    });

});



Route::middleware([
    'web',
    \App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class,
    'tenant_glvar',
    'setlang'
])->group(function () {


    /*----------------------------------------------------------------------------------------------------------------------------
    |                                                      FRONTEND ROUTES (Tenants)
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::middleware('package_expire')->controller(\Modules\Blog\Http\Controllers\Tenant\Frontend\BlogController::class)->prefix('blog')->name('tenant.')->group(function () {
        Route::get('/search/data', 'blog_search_page')->name('frontend.blog.search');
        Route::get('/{slug}', 'blog_single')->name('frontend.blog.single');
        Route::get('/category/{id}/{any?}', 'category_wise_blog_page')->name('frontend.blog.category');
        Route::get('/tags/{any?}', 'tags_wise_blog_page')->name('frontend.blog.tags.page');
        Route::get('/get/tags', 'get_tags_by_ajax')->name('frontend.get.taags.by.ajax');
        Route::post('/blog/comment/store', 'blog_comment_store')->name('frontend.blog.comment.store');
        Route::post('blog/all/comment', 'load_more_comments')->name('frontend.load.blog.comment.data');
    });

});


