<?php

namespace Modules\Donation\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Donation\Entities\DonationActivity;
use Modules\Donation\Entities\DonationActivityCategory;

class DonationActivityCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:donation-activity-category-list|donation-activity-category-create|donation-activity-category-edit|donation-activity-category-delete',['only' => ['index']]);
        $this->middleware('permission:donation-activity-category-create',['only' => ['new_category','store']]);
        $this->middleware('permission:donation-category-edit',['only' => ['update_category']]);
        $this->middleware('permission:donation-activity-category-delete',['only' => ['destroy','bulk_action','delete_category_all_lang']]);
    }

    public function index(Request $request)
    {
        $all_categories = DonationActivityCategory::select('id','title','slug','status')->get();
        return view('donation::tenant.backend.donations.activity.category')->with([
            'all_categories' => $all_categories,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }


    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:191|unique:donation_categories',
            'slug' => 'nullable|string|max:191',
            'status' => 'nullable|string|max:191',
        ]);

        $category = new DonationActivityCategory();
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->status = $request->status;
        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $created_slug = create_slug($slug,'DonationActivityCategory',true,'Donation');
        $category->slug = $created_slug;
        $category->save();
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:donation_categories',
            'slug' => 'required|string',
            'status' => 'nullable|string|max:191',
        ]);

        $category =  DonationActivityCategory::findOrFail($request->id);
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->status = $request->status;

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $created_slug = create_slug($slug,'DonationActivityCategory',true,'Donation');
        $category->slug = $category->slug == $request->slug ? $category->slug : $created_slug;
        $category->save();
        return response()->success(ResponseMessage::SettingsSaved());

    }

    public function destroy($id)
    {
        if (DonationActivity::where('category_id',$id)->first()){
            return redirect()->back()->with([
                'msg' => __('You can not delete this category, It is already associated with a post...'),
                'type' => 'danger'
            ]);
        }
        $category =  DonationActivityCategory::find($id);
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        DonationActivityCategory::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
