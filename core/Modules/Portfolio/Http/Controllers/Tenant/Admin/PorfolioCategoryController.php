<?php

namespace Modules\Portfolio\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Portfolio\Entities\Portfolio;
use Modules\Portfolio\Entities\PortfolioCategory;

class PorfolioCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:portfolio-category-list|portfolio-category-create|portfolio-category-edit|portfolio-category-delete',['only' => ['index']]);
        $this->middleware('permission:portfolio-category-create',['only' => ['store']]);
        $this->middleware('permission:portfolio-category-edit',['only' => ['update']]);
        $this->middleware('permission:portfolio-category-delete',['only' => ['destroy','bulk_action']]);
    }

    public function index(Request $request)
    {
        $all_categories = PortfolioCategory::select('id','title','status')->get();
        return view('portfolio::tenant.backend.portfolio.category')->with([
            'all_categories' => $all_categories,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:portfolio_categories',
            'status' => 'nullable|string|max:191',
        ]);

        $category = new PortfolioCategory();
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->status = $request->status;
        $category->save();
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:portfolio_categories',
            'status' => 'nullable|string|max:191',
        ]);

        $category =  PortfolioCategory::findOrFail($request->id);
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->status = $request->status;
        $category->save();
        return response()->success(ResponseMessage::SettingsSaved());

    }

    public function destroy($id)
    {
        if (Portfolio::where('category_id',$id)->first()){
            return redirect()->back()->with([
                'msg' => __('You can not delete this category, It is already associated with a post...'),
                'type' => 'danger'
            ]);
        }
        $category =  PortfolioCategory::where('id',$id)->first();
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        PortfolioCategory::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
