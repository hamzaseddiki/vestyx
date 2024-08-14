<?php

namespace Modules\Knowledgebase\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Knowledgebase\Entities\Knowledgebase;
use Modules\Knowledgebase\Entities\KnowledgebaseCategory;


class KnowledgebaseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:knowledgebase-category-list|knowledgebase-category-create|knowledgebase-category-edit|knowledgebase-category-delete',['only' => ['index']]);
        $this->middleware('permission:knowledgebase-category-create',['only' => ['store']]);
        $this->middleware('permission:knowledgebase-category-edit',['only' => ['update']]);
        $this->middleware('permission:knowledgebase-category-delete',['only' => ['destroy','bulk_action']]);
    }

    public function index(Request $request)
    {
        $all_categories = KnowledgebaseCategory::select('id','title','status','image')->get();
        return view('knowledgebase::tenant.backend.knowledgebases.category')->with([
            'all_categories' => $all_categories,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function store(Request $request)
    {
        $request_data = $request->validate([
            'title' => 'required',
            'status' => 'nullable',
            'image' => 'required|string|max:191',
        ]);

        $category = new KnowledgebaseCategory();
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request_data['title']));
        $category->image = $request_data['image'];
        $category->status = $request_data['status'];
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request)
    {
        $request_data = $request->validate([
            'title' => 'required|string|max:191|unique:knowledgebase_categories',
            'status' => 'nullable|string|max:191',
            'image' => 'required|string|max:191',
        ]);

        $category = KnowledgebaseCategory::findOrFail($request->id);
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request_data['title']));
        $category->image = $request_data['image'];
        $category->status = $request_data['status'];
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function destroy($id)
    {
        if (Knowledgebase::where('category_id',$id)->first()){
            return redirect()->back()->with([
                'msg' => __('You can not delete this category, It is already associated with a post...'),
                'type' => 'danger'
            ]);
        }
        $category =  KnowledgebaseCategory::where('id',$id)->first();
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        KnowledgebaseCategory::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

}
