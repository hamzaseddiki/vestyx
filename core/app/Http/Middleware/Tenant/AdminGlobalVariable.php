<?php

namespace App\Http\Middleware\Tenant;

use App\Models\ContactMessage;
use Closure;
use Illuminate\Http\Request;

class AdminGlobalVariable
{

    public function handle(Request $request, Closure $next)
    {

        $all_messages = ContactMessage::orderBy('id','desc')->take(3)->get();
        $new_message =  ContactMessage::where('status',1)->count();

        $share_data = [
            'all_messages' => $all_messages,
            'new_message' => $new_message
        ];
        view()->composer('*', function ($view) use ($share_data) {

            $view->with($share_data);

        });
        return $next($request);
    }
}
