<?php

namespace App\Http;

use App\Http\Middleware\Landlord\AdminGlobalVariable;
use App\Http\Middleware\Landlord\GlobalVariableMiddleware;
use App\Http\Middleware\Landlord\TenantAdminPanelMailVerifyMiddleware;
use App\Http\Middleware\Landlord\TenantMailVerifyMiddleware;
use App\Http\Middleware\MaintenanceModeMiddleware;
use App\Http\Middleware\Tenant\PackageAccessMiddleware;
use App\Http\Middleware\Tenant\PackageExpireMiddleware;
use App\Http\Middleware\Tenant\PageLimitMiddleware;
use App\Http\Middleware\Tenant\TenantAccountStatus;
use App\Http\Middleware\Tenant\TenantFeaturePermission;
use App\Http\Middleware\Tenant\TenantSocialLoginConfigureMiddleware;
use App\Http\Middleware\Tenant\TenantConfigureMiddleware;
use App\Http\Middleware\Tenant\TenantUserMailVerifyMiddleware;
use App\Http\Middleware\TenantCheckMiddleware;
use App\Http\Middleware\userMailVerifyMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Modules\TwoFactorAuthentication\Http\Middleware\Login2FaMiddleware;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SetLang::class,
            TenantConfigureMiddleware::class,
            TenantSocialLoginConfigureMiddleware::class,
            //\App\Http\Middleware\Demo::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            //'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            TenantConfigureMiddleware::class
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'maintenance_mode' => MaintenanceModeMiddleware::class,
        'userMailVerify' => userMailVerifyMiddleware::class,
        'landlord_glvar' => GlobalVariableMiddleware::class,
        'adminglobalVariable' => AdminGlobalVariable::class,
        'tenant_glvar' => \App\Http\Middleware\Tenant\GlobalVariableMiddleware::class,
        'tenant_admin_glvar' => \App\Http\Middleware\Tenant\AdminGlobalVariable::class,
        'tenant_auth_check' => TenantCheckMiddleware::class,
        'package_expire' => PackageExpireMiddleware::class,
        'tenantMailVerify' => TenantMailVerifyMiddleware::class,
        'tenantAdminPanelMailVerify' => TenantAdminPanelMailVerifyMiddleware::class,
        'tenantUserMailVerify' => TenantUserMailVerifyMiddleware::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
        'setlang' => \App\Http\Middleware\SetLang::class,
        'page_limit' => PageLimitMiddleware::class,
        'tenant_feature_permission' => TenantFeaturePermission::class,
        'tenant_status' => TenantAccountStatus::class,
        'Google2FA' => Login2FaMiddleware::class
    ];
}
