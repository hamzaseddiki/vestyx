<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\ImageGallery;
use App\Models\ImageGalleryCategory;
use Illuminate\Http\Request;

class ImageGalleryController extends Controller
{
    public function __construct()
    {
    }
    public function index(Request $request){

        $all_faqs = ImageGallery::select('id','title','image','status','category_id','subtitle')->get();
        $all_categories = ImageGalleryCategory::select('id','title')->get();
        return view('tenant.admin.image-gallery.gallery')->with([
            'all_faqs' => $all_faqs,
            'default_lang'=> $request->lang ?? GlobalLanguage::default_slug(),
            'all_categories' => $all_categories
        ]);
    }
    public function store(Request $request){
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'status' => 'required|string|max:191',
            'image' => 'required|string|max:191',
        ]);

        $testimonial = new ImageGallery();
        $testimonial->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title))
                    ->setTranslation('subtitle',$request->lang, SanitizeInput::esc_html($request->subtitle));
        $testimonial->category_id = $request->category_id;
        $testimonial->status = $request->status;
        $testimonial->image = $request->image;
        $testimonial->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request){
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'status' => 'required|string|max:191',
            'image' => 'required|string|max:191',
        ]);

        $testimonial = ImageGallery::find($request->id);
        $testimonial->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title))
                     ->setTranslation('subtitle',$request->lang, SanitizeInput::esc_html($request->subtitle));
        $testimonial->category_id = $request->category_id;
        $testimonial->status = $request->status;
        $testimonial->image = $request->image;
        $testimonial->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function clone(Request $request){
        $gallery = ImageGallery::find($request->item_id);
        ImageGallery::create([
            'title' => $gallery->getTranslation('title',default_lang()),
            'subtitle' => $gallery->getTranslation('subtitle',default_lang()),
            'status' => 0,
            'category_id' => $gallery->category_id,
            'image' => $gallery->image
        ]);
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete($id){
        ImageGallery::find($id)->delete();
        return response()->danger(ResponseMessage::delete('Image Gallery Item Deleted'));
    }

    public function bulk_action(Request $request){
        $all = ImageGallery::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

}
