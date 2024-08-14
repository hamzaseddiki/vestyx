<?php

namespace Database\Seeders\Tenant\ModuleData;

use App\Models\Menu;
use App\Models\PageBuilder;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeed extends Seeder
{
    public static function execute($page_id = null) // todo:: fixed by md zahid
    {
        $data = file_get_contents('assets/tenant/page-layout/menu.json');
        $all_data_decoded = json_decode($data) ?? (object) [];

        foreach ($all_data_decoded->data as $item){
            $menuItems = [];

            foreach (json_decode($item->content) as $menu){

                if($menu->ptype == 'pages'){
                    if(!empty($menu->pid) && $menu->pid != $page_id){
                        $menuItems[] = $menu;
                    }
                }else{
                    $menuItems[] = $menu;
                }
            }

            Menu::create([
                'title' => $item->title,
                'content' => json_encode($menuItems),
                'status' => $item->status,
            ]);
        }
    }

    private static function remove_new_line($data)
    {
        return (array) json_decode(str_replace(["\n","\r"],"",$data->addon_settings));
    }
}
