<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:testimonial-list|testimonial-create|testimonial-edit|testimonial-delete',['only' => ['index']]);
        $this->middleware('permission:testimonial-create',['only' => ['store']]);
        $this->middleware('permission:testimonial-edit',['only' => ['update','clone']]);
        $this->middleware('permission:testimonial-delete',['only' => ['delete','bulk_action']]);
    }
    public function index(Request $request){

        $all_testimonials = Testimonial::all();
        return view('landlord.admin.testimonial.index')->with([
            'all_testimonials' => $all_testimonials,
            'default_lang'=> $request->lang ?? GlobalLanguage::default_slug()
        ]);
    }
    public function store(Request $request){

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'description' => 'required|string|max:191',
            'designation' => 'required|string|max:191',
            'company' => 'nullable|string|max:191',
            'image' => 'nullable|string|max:191',
        ]);

        $testimonial = new Testimonial();
        $testimonial->setTranslation('name',$request->lang, SanitizeInput::esc_html($request->name))
            ->setTranslation('description',$request->lang, SanitizeInput::esc_html($request->description))
            ->setTranslation('designation',$request->lang, SanitizeInput::esc_html($request->designation))
            ->setTranslation('company',$request->lang, SanitizeInput::esc_html($request->company));
        $testimonial->image = $request->image;
        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request){
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'description' => 'required',
            'designation' => 'nullable|string|max:191',
            'company' => 'nullable|string|max:191',
            'image' => 'nullable|string|max:191',
        ]);


        $testimonial = Testimonial::find($request->id);
        $testimonial->setTranslation('name',$request->lang, SanitizeInput::esc_html($request->name))
            ->setTranslation('description',$request->lang, SanitizeInput::esc_html($request->description))
            ->setTranslation('designation',$request->lang, SanitizeInput::esc_html($request->designation))
            ->setTranslation('company',$request->lang, SanitizeInput::esc_html($request->company));
        $testimonial->image = $request->image;
        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function clone(Request $request){
        $testimonial = Testimonial::find($request->item_id);
        Testimonial::create([
            'name' => $testimonial->getTranslation('name',default_lang()),
            'description' => $testimonial->getTranslation('description',default_lang()),
            'status' => 0,
            'designation' => $testimonial->getTranslation('designation',default_lang()),
            'company' => $testimonial->getTranslation('company',default_lang()),
            'image' => $testimonial->image
        ]);
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete(Request $request,$id){
        Testimonial::find($id)->delete();
        return response()->danger(ResponseMessage::delete('Testimonial Deleted'));
    }

    public function bulk_action(Request $request){
        $all = Testimonial::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

}
