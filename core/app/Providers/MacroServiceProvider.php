<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $flash_message = ['success','warning','info','danger','primary'];
        foreach ($flash_message as $flmsg){
            Response::macro($flmsg,function ($text) use ($flmsg){
                return back()->with(['msg' => $text, 'type' => $flmsg]);
            });
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
