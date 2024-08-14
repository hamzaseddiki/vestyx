<?php

namespace Modules\Product\Http\Middleware;

use App\Helpers\FlashMsg;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Product\Entities\Product;

class ProductLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        (string) $getRouteName = Route::currentRouteName();
        $routeArray = explode('.', $getRouteName);

        $product_count = Product::withTrashed()->count();
        $product_limit = tenant()?->payment_log?->package?->product_create_permission;

        if ($product_count >= $product_limit)
        {
            if (!empty($routeArray) && end($routeArray) == 'clone')
            {
                return back()->with(FlashMsg::explain('danger','You can not upload more products due to your product upload limit!'));
            }
            return response()->json(["restricted" => true]);
        }

        return $next($request);
    }
}
