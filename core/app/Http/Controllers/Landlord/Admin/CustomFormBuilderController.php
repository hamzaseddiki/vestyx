<?php

namespace App\Http\Controllers\Landlord\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomFormBuilderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:form-builder');
    }

    private const BASE_PATH = 'landlord.admin.form-builder.custom.';

    public function all(Request $request){
        $all_forms = FormBuilder::all();
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        return view(self::BASE_PATH.'all',compact('all_forms','default_lang'));
    }
    public function bulk_action(Request $request){
        FormBuilder::whereIn('id',$request->ids)->delete();
        return response()->json('ok');
    }
    public function edit(Request $request, $id){
        $form =  FormBuilder::findOrFail($id);
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        return view(self::BASE_PATH.'edit',compact('form','default_lang'));
    }
    public function update(Request $request){
        $this->validate($request,[
            'title' => 'required|string',
            'email' => 'required|string',
            'button_title' => 'required|string',
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
            'success_message' => 'required',
        ]);
        $id = $request->id;
        $title = $request->title;
        $button_text = $request->button_title;
        $success_message = $request->success_message;
        $email = $request->email;
        unset($request['_token'],$request['email'],$request['button_title'],$request['title'],$request['id']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname){
            $all_fields_name[] = strtolower(Str::slug($fname));
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        $form_builder = FormBuilder::findOrfail($id);
        $form_builder->setTranslation('title',$request->lang,$title) ;
        $form_builder->setTranslation('button_text',$request->lang,$button_text) ;
        $form_builder->setTranslation('success_message',$request->lang,$success_message) ;
        $form_builder->email = $email;
        $form_builder->fields = $json_encoded_data;
        $form_builder->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function store(Request $request){
        $this->validate($request,[
            'title' => 'required|string',
            'email' => 'required|string',
            'button_title' => 'required|string',
            'success_message' => 'required|string',
        ]);

        $form_builder = new FormBuilder();
        $form_builder->setTranslation('title',$request->lang,$request->title) ;
        $form_builder->setTranslation('button_text',$request->lang,$request->button_title) ;
        $form_builder->setTranslation('success_message',$request->lang,$request->success_message) ;
        $form_builder->email = $request->email;
        $form_builder->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete($id){
        FormBuilder::findOrFail($id)->delete();
        return response()->danger(ResponseMessage::delete());
    }
}
