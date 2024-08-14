<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{
    public function __construct()
    {
    }

    public function get_in_touch_form_index(){
        return view('landlord.admin.form-builder.contact-form');
    }
    public function update_get_in_touch_form(Request $request){

        $this->validate($request,[
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
        ]);

        unset($request['_token']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname){
            $all_fields_name[] = strtolower(Str::slug($fname));
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        update_static_option('get_in_touch_form_fields',$json_encoded_data);
        return redirect()->back()->with(['msg' => __('Form Updated...'),'type' => 'success']);
    }



}
