<?php

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

use Illuminate\Support\Facades\Route;
use Modules\ChargilyPayPaymentMethod\Http\Controllers\AdminChargilyPayPaymentMethodController;
use Modules\ChargilyPayPaymentMethod\Http\Controllers\ChargilyPayPaymentMethodController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/* frontend routes */

Route::prefix('chargilypaypaymentmethod')->group(function () {
    Route::post("landlord-price-plan-chargilypay", [ChargilyPayPaymentMethodController::class, "landlordPricePlanIpn"])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
        ->name("chargilypaypaymentmethod.landlord.price.plan.ipn");
});


/* tenant payment ipn route*/
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class
])->prefix('chargilypaypaymentmethod')->group(function () {
    Route::post("tenant-price-plan-chargilypay", [ChargilyPayPaymentMethodController::class, "TenantChargilyPayIpn"])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
        ->name("chargilypaypaymentmethod.tenant.price.plan.ipn");
});

/* admin panel routes landlord */
Route::group(['middleware' => ['auth:admin', 'adminglobalVariable', 'set_lang'], 'prefix' => 'admin-home'], function () {
    Route::prefix('chargilypaypaymentmethod')->group(function () {
        Route::get('/settings', [AdminChargilyPayPaymentMethodController::class, "settings"])->name("chargilypaypaymentmethod.landlord.admin.settings");
        Route::post('/settings', [AdminChargilyPayPaymentMethodController::class, "settingsUpdate"]);
    });
});


Route::group(['middleware' => [
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:admin',
    'tenant_admin_glvar',
    'package_expire',
    'tenantAdminPanelMailVerify',
    'tenant_status',
    'set_lang'
], 'prefix' => 'admin-home'], function () {
    Route::prefix('chargilypaypaymentmethod/tenant')->group(function () {
        Route::get('/settings', [AdminChargilyPayPaymentMethodController::class, "settings"])->name("chargilypaypaymentmethod.tenant.admin.settings");
        Route::post('/settings', [AdminChargilyPayPaymentMethodController::class, "settingsUpdate"]);
    });
});
