<?php

namespace App\Http\Middleware\Landlord;

use App\Models\ContactMessage;
use App\Models\Notification;
use Closure;
use Illuminate\Http\Request;

class AdminGlobalVariable
{

    public function handle(Request $request, Closure $next)
    {

        $all_messages = ContactMessage::orderBy('id','desc')->take(3)->get() ?? [];
        $new_message =  ContactMessage::where('status',1)->count() ?? 0;

        $all_notifications = [];
        $new_notification = 0;

        if(!tenant()){

            try {
                $all_notifications = Notification::orderBy('id','desc')->take(3)->get() ?? [];
                $new_notification =  Notification::where('status',0)->count() ?? 0;
            }catch(\Exception $e){}
        }

        $share_data = [
            'all_messages' => $all_messages,
            'new_message' => $new_message,
            'all_notifications' => $all_notifications,
            'new_notification' => $new_notification
        ];

        view()->composer('*', function ($view) use ($share_data) {

            $view->with($share_data);

        });

        return $next($request);
    }
}
