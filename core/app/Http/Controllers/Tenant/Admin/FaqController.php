<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __construct()
    {
    }
    public function index(Request $request){

        $all_faqs = Faq::select('id','title','description','status','category_id')->get();
        $all_categories = FaqCategory::select('id','title')->get();
        return view('tenant.admin.faq.faq')->with([
            'all_faqs' => $all_faqs,
            'default_lang'=> $request->lang ?? GlobalLanguage::default_slug(),
            'all_categories' => $all_categories
        ]);
    }
    public function store(Request $request){
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'description' => 'required',
        ]);

        $testimonial = new Faq();
        $testimonial->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title))
                    ->setTranslation('description',$request->lang, SanitizeInput::esc_html($request->description));
        $testimonial->category_id = $request->category_id;
        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request){
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'description' => 'required',
        ]);

        $testimonial = Faq::find($request->id);
        $testimonial->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title))
                     ->setTranslation('description',$request->lang, SanitizeInput::esc_html($request->description));
        $testimonial->category_id = $request->category_id;
        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete($id){
        Faq::find($id)->delete();
        return response()->danger(ResponseMessage::delete('Faq Item Deleted'));
    }

    public function bulk_action(Request $request){
        $all = Faq::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

}
