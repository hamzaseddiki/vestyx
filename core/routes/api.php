<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*----------------------------------------------------------------------
    API ROUTES FOR  TENANT ADMIN DASHBOARD SETTINGS
-----------------------------------------------------------------------*/
Route::controller(\App\Http\Controllers\Landlord\Api\TenantProfileUpdate::class)->middleware(['auth:sanctum'])->prefix('tenant')->group(function (){
    /* ---------------------------
      PROFILE RELATED ROUTES
    -----------------------------*/
    Route::post('edit-profile','edit_profile');
    Route::post('change-password','change_password');

    /* ---------------------------
        PAYMENT RELATED ROUTES
    -----------------------------*/

    /* ---------------------------
        SUPPORT TICKET ROUTES
    -----------------------------*/
});
