<?php

namespace App\Http\Middleware\Landlord;

use App\Facades\GlobalLanguage;
use App\Models\Menu;
use App\Models\StaticOption;
use Closure;

class GlobalVariableMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = GlobalLanguage::user_lang_slug();

        $primary_menu = Menu::where(['status' => 'default'])->first();

        $popup_id = get_static_option('popup_selected_'.$lang.'_id');

        $website_url = url('/');
        $all_social_icons = [];


        //make a function to call all static option by home page
        $static_option_arr = [
            'site_white_logo',
            'og_meta_image_for_site',
            'site_color',
            'site_logo',
            'site_third_party_tracking_code',
            'site_favicon',
            'item_license_status',
            'site_script_unique_key',
            'site_'.$lang.'_title',
            'site_'.$lang.'_tag_line',
//            meta info
            'site_'.$lang.'_meta_title',
            'site_'.$lang.'_meta_tags',
            'site_'.$lang.'_meta_keywords',
            'site_'.$lang.'_meta_description',
//            og meta
            'site_'.$lang.'_og_meta_title',
            'site_'.$lang.'_og_meta_description',
            'site_'.$lang.'_og_meta_image',
            //color variable
            'main_color_one',
            'main_color_two',
            'secondary_color',
            'heading_color',
            'paragraph_color',
            'gradient_one_color',
            'gradient_two_color',
            'main_color_one',
            'main_color_two',
            'heading_color',
            'secondary_color',
            'paragraph_color',
            'heading_font_family',
            'body_font_family',

        ];
        $static_field_data = StaticOption::whereIn('option_name',$static_option_arr)->get()->mapWithKeys(function ($item) {
            return [$item->option_name => $item->option_value];
        })->toArray();

        $all_data = [
            'primary_menu' => $primary_menu,
            'global_static_field_data' => $static_field_data,
            'user_select_lang_slug'=> $lang,
            'all_social_icons'=> $all_social_icons,
        ];
        view()->composer([
            'landlord/frontend/*',
            'tenant/frontend/*',
            'components/*',
        ], function ($view) use ($all_data){
            $view->with($all_data);
        });

        return $next($request);
    }
}
