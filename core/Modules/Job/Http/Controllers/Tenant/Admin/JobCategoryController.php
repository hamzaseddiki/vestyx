<?php

namespace Modules\Job\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Job\Entities\Job;
use Modules\Job\Entities\JobCategory;


class JobCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:job-category-list|job-category-create|job-category-edit|job-category-delete',['only' => ['index']]);
        $this->middleware('permission:job-category-create',['only' => ['store']]);
        $this->middleware('permission:job-category-edit',['only' => ['update']]);
        $this->middleware('permission:job-category-delete',['only' => ['destroy','bulk_action']]);
    }

    public function index(Request $request)
    {
        $all_categories = JobCategory::select('id','title','status','subtitle','image')->get();
        return view('job::tenant.backend.jobs.category')->with([
            'all_categories' => $all_categories,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'nullable',
        ]);

        $category = new JobCategory();
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->setTranslation('subtitle',$request->lang, SanitizeInput::esc_html($request->subtitle));
        $category->image = $request->image;
        $category->status = $request->status;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:event_categories',
            'status' => 'nullable|string|max:191',
        ]);

        $category = JobCategory::findOrFail($request->id);
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->setTranslation('subtitle',$request->lang, SanitizeInput::esc_html($request->title));
        $category->image = $request->image;
        $category->status = $request->status;
        $category->save();
        return response()->success(ResponseMessage::SettingsSaved());

    }

    public function destroy($id)
    {
        if (Job::where('category_id',$id)->first()){
            return redirect()->back()->with([
                'msg' => __('You can not delete this category, It is already associated with a post...'),
                'type' => 'danger'
            ]);
        }
        $category =  JobCategory::where('id',$id)->first();
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        JobCategory::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

}
