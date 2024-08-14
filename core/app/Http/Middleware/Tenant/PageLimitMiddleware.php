<?php

namespace App\Http\Middleware\Tenant;

use App\Helpers\FlashMsg;
use App\Models\Page;
use Closure;
use Illuminate\Http\Request;

class PageLimitMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (!is_null(tenant()))
        {
            $page_count = Page::count();
            $page_limit = tenant()?->payment_log?->package?->page_permission_feature;

            if ($page_limit != -1 && $page_count >= $page_limit)
            {
                return back()->with(FlashMsg::explain('danger',__('You can not upload more pages due to your page upload limit!')));
            }
        }

        return $next($request);
    }
}
