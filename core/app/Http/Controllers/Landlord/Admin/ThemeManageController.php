<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Facades\ThemeDataFacade;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Themes;
use Illuminate\Http\File as HttpFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ThemeManageController extends Controller
{
    public function __construct()
    {

    }

    public function all_theme()
    {
        return view('landlord.admin.themes.index');
    }

    public function update_status(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required'
        ]);

        $theme_file = '';
        $filePath =  theme_path($data['slug']).'/theme.json';
        if (file_exists($filePath) && !is_dir($filePath)){
            //cache data for 10days
            $theme_file = json_decode(file_get_contents($filePath), false);
            if (!empty($theme_file))
            {
                $theme_file->status = !$theme_file->status;
                file_put_contents($filePath ,json_encode($theme_file));
            }
        }
        $status = $theme_file->status == false ? __('Inactive') : __('Active');
        return response()->json([
            'status' => $status,
            'msg' => 'The theme is '.$status.' successfully'
        ]);
    }

    public function update_theme(Request $request)
    {
        $theme_data = [
            'theme_slug' => 'required',
            'theme_url' => 'nullable',
            'theme_image' => 'nullable',
            'theme_is_available' => 'nullable'
        ];


        foreach (\App\Facades\GlobalLanguage::all_languages(1) as $lang){
            $theme_slug = $request->theme_slug;

            $translation_fields = [
                'theme_name_'.$lang->slug,
                'theme_description_'.$lang->slug,
            ];


            foreach ($translation_fields as $item){
                $value = $item;
                update_static_option_central($theme_slug.'_'.$item,$request->$value );
            }

        }

        $this->validate($request, $theme_data);
        $image_id = $request->theme_image;
        $availability = $request->theme_is_available;


        $image = get_attachment_image_by_id($request->theme_image);
        $image_url = !empty($image) ? $image['img_url'] : '';

        unset($theme_data['theme_slug']);
        $request['theme_image'] = $image_url;

        foreach ($theme_data as $field_name => $rules){
            update_static_option_central($request->theme_slug.'_'.$field_name, $request->$field_name);
        }
        update_static_option_central($request->theme_slug.'_theme_image_id', $image_id);
        update_static_option_central($request->theme_slug.'_theme_is_available', $availability);


        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function theme_settings()
    {
        return view('landlord.admin.themes.settings');
    }
    public function add_new_theme()
    {
        return view('landlord.admin.themes.new');
    }
    public function store_theme(Request $request)
    {
        $request->validate([
            "theme_file" => "required|file|mimes:zip|max:200000"
        ]);

        //todo work for upload plugin
        //todo validate the plugin file is valid or not.. it has contain nazmart meta data or not
        $theme_file = $request->file('theme_file');

        if (!$request->hasFile('theme_file')){
            return back()->with(['msg' => __('inter a valid theme file'),'type' => 'danger']);
        }

        if ($this->replaceThemeFile($theme_file)){
            $file_name = pathinfo($theme_file->getClientOriginalName(),PATHINFO_FILENAME);
            return redirect()->back()->with(["msg" => $file_name." ".__("upload success, now you can use the theme"),"type" => "success"]);
        }

        return back()->with(["msg" => __("the file you have uploaded it not a valid theme.."),"type" => "danger"]);
        //todo if plugin file uploaded show option to active that plugin.. or redirect theme to all plugins page

    }

    private  function  replaceThemeFile($file){
        $theme_name = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

        $storage_path = storage_path('/themes-file');
        if (!file_exists($storage_path) && !is_dir($storage_path)){
            mkdir($storage_path,777,true);
        }

        $uploaded_plugin_path = Storage::putFile('/themes-file', $file);

        $getLatestUpdateFile = storage_path('app/'.$uploaded_plugin_path);
        $zipArchive = new \ZipArchive();
        $zipArchive->open($getLatestUpdateFile);

        $updatedFileLocation = "themes-file/".$theme_name;

        $zipExtracted = $zipArchive->extractTo(storage_path('app/themes-file'));

        if ($zipExtracted) {
            $zipArchive->close();
            //delete zip after extracted
            @unlink(storage_path('app/'.$uploaded_plugin_path));
            //todo move full folder into module_path folder
            $updateFiles = Storage::allFiles($updatedFileLocation);
            if (!in_array("themes-file/".$theme_name."/theme.json",$updateFiles)){
                return false;
            }
            //todo get modules.json file content
            $plugin_info = json_decode(file_get_contents(storage_path("app/themes-file/".$theme_name."/theme.json")));

            if (!property_exists($plugin_info,"name")){
                return false;
            }


            foreach($updateFiles as $updateFile) {
                $folderName = pathinfo($updateFile,PATHINFO_DIRNAME);
                $fileName = pathinfo($updateFile,PATHINFO_FILENAME);
                if (
                    str_contains($folderName, '.vscode') ||
                    str_contains($folderName, '.idea') ||
                    str_contains($folderName, '.fleet') ||
                    str_contains($folderName, '.git')){
                    continue;
                }
                $file = new HttpFile(storage_path("app/" . $updateFile));
                $skipFiles = ['.DS_Store','.gitkeep'];
                if (!in_array($fileName,$skipFiles)){
                    $file->move(storage_path('../resources/views/themes/' . str_replace("themes-file/","",$folderName)));
                }
            }

        }

        Storage::deleteDirectory($updatedFileLocation);

        return true;
    }

    public function theme_settings_update(Request $request)
    {
        $this->validate($request, [
            'up_coming_themes_backend' => 'nullable|string',
            'up_coming_themes_frontend' => 'nullable|string',
        ]);
        update_static_option('up_coming_themes_backend', $request->up_coming_themes_backend);
        update_static_option('up_coming_themes_frontend', $request->up_coming_themes_frontend);

        return redirect()->back()->with([
            'msg' => __('Theme Settings Updated ...'),
            'type' => 'success'
        ]);
    }

}
