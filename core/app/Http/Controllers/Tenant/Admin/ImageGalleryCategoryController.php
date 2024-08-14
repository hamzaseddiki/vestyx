<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\ImageGalleryCategory;
use Illuminate\Http\Request;

class ImageGalleryCategoryController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request){

        $all_faq_categories = ImageGalleryCategory::select('id','title','status')->get();
        return view('tenant.admin.image-gallery.category')->with([
            'all_faq_categories' => $all_faq_categories,
            'default_lang'=> $request->lang ?? GlobalLanguage::default_slug()
        ]);
    }
    public function store(Request $request){

        $this->validate($request,[
            'title' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);

        $testimonial = new ImageGalleryCategory();
        $testimonial->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request){

        $this->validate($request,[
            'title' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);

        $testimonial = ImageGalleryCategory::find($request->id);
        $testimonial->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function delete(Request $request,$id){
        ImageGalleryCategory::find($id)->delete();
        return response()->danger(ResponseMessage::delete('Testimonial Deleted'));
    }

    public function bulk_action(Request $request){
        $all = ImageGalleryCategory::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

}
