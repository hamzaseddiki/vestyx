<?php

namespace App\Facades;


use App\Helpers\SidebarMenuHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see SidebarMenuHelper::class
 * @method render_sidebar_menus()
 *
 * */
class LandlordAdminMenu extends Facade
{
   public static function getFacadeAccessor(){
       return 'LandlordAdminMenu';
   }
}
