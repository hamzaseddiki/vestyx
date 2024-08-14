<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Plugins\PageBuilder\PageBuilderSetup;

class PageBuilderController extends Controller
{
    const BASE_PATH = 'admin.page-builder.';

    public function dynamicpage_builder($type,$id){
        if (empty($type) || empty($id)){
            abort(404);
        }
        $page = Page::findOrFail($id);
        return view(self::BASE_PATH.'dynamicpage',compact('id','type','page'));
    }



    public function get_admin_panel_addon_markup(Request $request){
        $output = PageBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $request->addon_class,
            'namespace' => base64_decode( $request->addon_namespace),
            'type' => 'new',
            'page_id' => $request->addon_page_id ?? '',
            'page_type' => $request->addon_page_type ?? '',
            'location' => $request->addon_location ?? '',
            'after' => false,
            'before' => false,
        ]);
        return $output;
    }

    public function store_new_addon_content(Request $request){
        $this->validate($request,[
            'addon_name' => 'required',
            'addon_namespace' => 'required',
            'addon_order' => 'nullable',
            'addon_location' => 'required',
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

        $widget_id =  PageBuilder::create([
            'addon_type' => $request->addon_type,
            'addon_location' => $request->addon_location,
            'addon_name' => $request->addon_name,
            'addon_namespace' => base64_decode($request->addon_namespace),
            'addon_page_id' => $request->addon_page_id,
            'addon_order' => $request->addon_order,
            'addon_page_type' => $request->addon_page_type,
            'addon_settings' => json_encode($widget_content),
        ])->id;
        $data['id'] = $widget_id;
        $data['status'] = 'ok';
        return response()->json($data);
    }

    public function delete(Request $request){
       PageBuilder::findOrFail($request->id)->delete();
       Cache::forget('widget_settings_cache'.$request->id);
       return response()->json('ok');
    }

    public function update_addon_order(Request $request){
        Cache::forget('widget_settings_cache'.$request->id);
        PageBuilder::findOrFail($request->id)->update(['addon_order' => $request->addon_order]);
        return response()->json('ok');
    }

    public function update_addon_content(Request $request){
        $this->validate($request,[
            'addon_name' => 'required',
            'addon_namespace' => 'required',
            'addon_order' => 'nullable',
            'addon_location' => 'required',
        ]);

        unset($request['_token']);
        $addon_content = (array) $request->all();
        Cache::forget('widget_settings_cache'.$request->id);
        PageBuilder::findOrFail($request->id)->update([
            'addon_type' => $request->addon_type,
            'addon_location' => $request->addon_location,
            'addon_name' => $request->addon_name,
            'addon_namespace' => base64_decode($request->addon_namespace),
            'addon_page_id' => $request->addon_page_id,
            'addon_order' => $request->addon_order,
            'addon_page_type' => $request->addon_page_type,
            'addon_settings' => json_encode($addon_content),
        ]);

        return response()->json('ok');
    }

}
