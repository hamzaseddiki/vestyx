<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use App\Models\StaticOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class LanguagesController extends Controller
{
    const BASE_PATH = 'landlord.admin.languages.';

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:language-list|language-edit|language-create|language-delete', ['only' => ['index', 'make_default']]);
        $this->middleware('permission:language-create', ['only' => ['store']]);
        $this->middleware('permission:language-edit', ['only' => ['backend_edit_words', 'frontend_edit_words', 'update_words', 'update', 'add_new_string', 'clone']]);
        $this->middleware('permission:language-delete', ['only' => ['delete']]);
    }
    public function index()
    {
        $all_lang = Language::all();

        return view(self::BASE_PATH.'index',compact('all_lang'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [

            'name' => 'required|string|max:191',
            'direction' => 'required|string|max:191',
            'slug' => 'required|unique:languages,slug|string|max:191',
            'status' => 'required|string|max:191',
        ]);

//        $language = Language::where('slug',$request->slug)->first();
//
//        if(!empty($language)){
//            return redirect()->back()->with([
//                'msg' => __('Language already exists..!'),
//                'type' => 'danger'
//            ]);
//        }

        Language::create([
            'name' => $request->name,
            'direction' => $request->direction,
            'slug' => $request->slug,
            'status' => $request->status,
            'default' => 0
        ]);


        //Passing data central from landlord
        if (!tenant()){
                $languages = Language::select('id','slug','name')->get();
                $item = [];
                foreach ($languages as $lang){
                    $item[$lang->slug] = $lang->name;
                }
                update_static_option_central('all_central_langs',json_encode($item));
            }
        //Passing data central from landlord


        //generate admin panel string
        if (!tenant() && !file_exists(resource_path('lang/') . $request->slug . '.json')){
            $backend_default_lang_data = file_get_contents(resource_path('lang/') . 'default.json');
            file_put_contents(resource_path('lang/') . $request->slug . '.json', $backend_default_lang_data);
        }

        return redirect()->back()->with([
            'msg' => __('New Language Added Success...'),
            'type' => 'success'
        ]);
    }

    public function all_edit_words($slug)
    {

        if(!file_exists(resource_path('lang/') . $slug . '.json') && !is_dir(resource_path('lang/') . $slug . '.json')){
            $backend_default_lang_data = file_get_contents(resource_path('lang/') . 'default.json');
            file_put_contents(resource_path('lang/') . $slug . '.json', $backend_default_lang_data);
        }
        $all_word = file_get_contents(resource_path('lang/') . $slug . '.json');

        return view(self::BASE_PATH.'edit-words')->with([
            'all_word' => json_decode($all_word),
            'lang_slug' => $slug,
            'type' => 'admin',
            'language' => Language::where('slug',$slug)->first()
        ]);
    }


    public function update_words(Request $request, $id)
    {

        $this->validate($request,[
            'string_key' => 'required',
            'translate_word' => 'required',
        ],[
            'type.required' => __('type is missing'),
            'string_key.required' => __('select source text'),
            'translate_word.required' => __('add translate text'),
        ]);

        $slug = $request->slug;
        //todo get text json file
        //todo get current key index and replace it in the json file
        if (file_exists(resource_path('lang/') . $slug .'.json')) {
            $default_lang_data = file_get_contents(resource_path('lang') . '/'.$slug .'.json');
            $default_lang_data = (array)json_decode($default_lang_data);
            $default_lang_data[$request->string_key] = $request->translate_word;
            $default_lang_data = (object)$default_lang_data;
            $default_lang_data = json_encode($default_lang_data);
            file_put_contents(resource_path('lang/') . $slug . '.json', $default_lang_data);
        }

        return response()->json([
            'type' => 'success',
            'msg' =>  __('Words Change Success')
        ]);
    }

    public function update(Request $request)
    {
        $userId = $request->id;
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'direction' => 'required|string|max:191',
            'status' => 'required|string|max:191',
            'slug' => 'required|string|max:191|unique:languages,slug,'. @$userId,
        ]);
        Language::where('id', $request->id)->update([
            'name' => $request->name,
            'direction' => $request->direction,
            'status' => $request->status,
            'slug' => $request->slug
        ]);


        if(!tenant()){
            $backend_lang_file_path = resource_path('lang/') . $request->slug . '.json';
            $frontend_lang_file_path = resource_path('lang/') . $request->slug . '.json';

            if (!file_exists($backend_lang_file_path) && !is_dir($backend_lang_file_path)) {
                file_put_contents($backend_lang_file_path, file_get_contents(resource_path('lang/') . 'default.json'));
            }

            if (!file_exists($frontend_lang_file_path) && !is_dir($frontend_lang_file_path)) {
                file_put_contents($frontend_lang_file_path, file_get_contents(resource_path('lang/')  . 'default.json'));
            }
        }


        return redirect()->back()->with([
            'msg' => __('Language Update Success...'),
            'type' => 'success'
        ]);
    }

    public function delete(Request $request, $id)
    {

        $lang = Language::find($id);
        $all_static_option = StaticOption::where('option_name', 'regexp', '_' . $lang->slug . '_')->get();

        foreach ($all_static_option as $option) {
            StaticOption::find($option->id)->delete();
        }

        $all_lang_models = [
            Page::all(),
        ];

        foreach ($all_lang_models as $model) {
            foreach ($model as $item) {
                $item->forgetAllTranslations($lang->slug)->save();
            }
        }

        $lang->delete();

        if (!tenant()){
            if (file_exists(resource_path('lang/') . $lang->slug . '.json')) {
                unlink(resource_path('lang/') . $lang->slug . '.json');
            }
        }

        return redirect()->back()->with([
            'msg' => __('Language Delete Success...'),
            'type' => 'danger'
        ]);
    }

    public function make_default(Request $request, $id)
    {
        Language::where('default', 1)->update(['default' => 0]);
        Language::find($id)->update(['default' => 1]);
        $lang = Language::find($id);
        $lang->default = 1;
        $lang->direction = $lang->slug == 'ar' ? 1 : 0;
        $lang->save();

        session()->put('lang', $lang->slug);
        //todo make a central option record about default database slug, so that we can use it in tenant website
        update_static_option_central('landlord_default_language_slug',$lang->slug);

        return redirect()->back()->with([
            'msg' => __('Default Language Set To') . ' ' . $lang->name,
            'type' => 'success'
        ]);
    }

    public function clone_languages(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required|string',
            'direction' => 'required|string',
            'status' => 'required|string',
            'slug' => 'required|string',
        ]);

        $clone_lang = Language::find($request->id);
        Language::create([
            'name' => $request->name,
            'direction' => $request->direction,
            'slug' => $request->slug,
            'status' => $request->status,
            'default' => 0
        ]);

        $search_term = '_' . $clone_lang->slug . '_';
        $all_static_option = StaticOption::where('option_name', 'regexp', $search_term)->get();
        foreach ($all_static_option as $option) {
            $option_name = str_replace($search_term, '_' . $request->slug . '_', $option->option_name);
            StaticOption::create([
                'option_name' => $option_name,
                'option_value' => $option->option_value
            ]);
        }

        $backend_default_lang_data = file_get_contents(resource_path('lang') . '/' . $clone_lang->slug . '.json');
        file_put_contents(resource_path('lang/') . $request->slug . '.json', $backend_default_lang_data);


        return redirect()->back()->with([
            'msg' => __('Language clone success with content...'),
            'type' => 'success'
        ]);
    }


    public function add_new_words(Request $request)
    {

        $this->validate($request, [
            'lang_slug' => 'required|string',
            'new_string' => 'required|string',
            'translate_string' => 'required|string',
        ]);

        if (file_exists(resource_path('lang/') . $request->lang_slug . '.json')) {
            $default_lang_data = file_get_contents(resource_path('lang/') . $request->lang_slug .'.json');
            $default_lang_data = (array)json_decode($default_lang_data);
            $default_lang_data[$request->new_string] = $request->translate_string;
            $default_lang_data = (object)$default_lang_data;
            $default_lang_data = json_encode($default_lang_data);
            file_put_contents(resource_path('lang/') . $request->lang_slug . '.json', $default_lang_data);
        }

        return back()->with(['msg' => __('New Word Added'), 'type' => 'success']);
    }

    public function regenerate_source_text(Request $request){

        $this->validate($request,[
            'slug' => 'required'
        ]);

        if (file_exists(resource_path('lang/') . $request->slug . '.json')){
            @unlink(resource_path('lang/') . $request->slug . '.json');
        }
        Artisan::call('translatable:export '.$request->slug );
        return back()->with(['msg' => __('Source text generate success'), 'type' => 'success']);
    }

    public function add_new_string(Request $request)
    {
        $this->validate($request, [
            'slug' => 'required',
            'string' => 'required',
            'translate_string' => 'required',
        ]);
        if (file_exists(resource_path('lang/') . $request->slug . '.json')) {
            $default_lang_data = file_get_contents(resource_path('lang') . '/' . $request->slug  . '.json');
            $default_lang_data = (array) json_decode($default_lang_data);
            $default_lang_data[$request->string] = $request->translate_string;
            $default_lang_data = (object) $default_lang_data;
            $default_lang_data =   json_encode($default_lang_data);
            file_put_contents(resource_path('lang/') . $request->slug . '.json', $default_lang_data);
        }
        return redirect()->back()->with([
            'msg' => __('new translated string added..'),
            'type' => 'success'
        ]);
    }
}
