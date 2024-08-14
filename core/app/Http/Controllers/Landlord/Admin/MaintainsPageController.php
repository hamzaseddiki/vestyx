<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class MaintainsPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:page-settings-maintain-page-manage');
    }

    public function maintains_page_settings()
    {
        $all_languages = Language::all();
        return view('landlord.admin.maintain-page.maintain-page-index')->with(['all_languages' => $all_languages]);
    }

    public function update_maintains_page_settings(Request $request)
    {
        $this->validate($request, [
            'maintenance_logo' => 'nullable|string|max:191',
            'maintenance_bg_image' => 'nullable|string|max:191',
        ]);

        $all_languages = Language::all();

        foreach ($all_languages as $lang) {

            $this->validate($request, [
                'maintains_page_' . $lang->slug . '_title' => 'nullable|string',
                'maintains_page_' . $lang->slug . '_description' => 'nullable|string'
            ]);
            $title =  'maintains_page_' . $lang->slug . '_title';
            $description =  'maintains_page_' . $lang->slug . '_description';
            $date = 'mentenance_back_date';

            update_static_option($title, $request->$title);
            update_static_option($description, $request->$description);
            update_static_option($date, $request->$date);
        }
        if (!empty($request->maintenance_logo)) {
            update_static_option('maintenance_logo', $request->maintenance_logo);
        }
        if (!empty($request->maintenance_bg_image)) {
            update_static_option('maintenance_bg_image', $request->maintenance_bg_image);
        }

        return redirect()->back()->with(['msg' => __('Settings Updated....'), 'type' => 'success']);
    }
}
