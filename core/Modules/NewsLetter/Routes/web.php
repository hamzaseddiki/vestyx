<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::prefix('admin-home')->middleware(['setlang:admin', 'adminglobalVariable','setlang'])->group(function () {
    Route::group(['prefix' => 'newsletter'], function () {
        Route::controller('AdminNewsLetterController')->group(function () {
            Route::get('/', 'index')->name('admin.newsletter');
            Route::post('/delete/{id}', 'delete')->name('admin.newsletter.delete');
            Route::post('/single', 'send_mail')->name('admin.newsletter.single.mail');
            Route::get('/all', 'send_mail_all_index')->name('admin.newsletter.mail');
            Route::post('/all', 'send_mail_all');
            Route::post('/new', 'add_new_sub')->name('admin.newsletter.new.add');
            Route::post('/bulk-action', 'bulk_action')->name('admin.newsletter.bulk.action');
            Route::post('/newsletter/verify-mail-send', 'verify_mail_send')->name('admin.newsletter.verify.mail.send');
        });
    });
});*/
