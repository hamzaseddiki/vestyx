<?php

namespace Modules\Event\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;

class EventCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:event-category-list|event-category-create|event-category-edit|event-category-delete',['only' => ['index']]);
        $this->middleware('permission:event-category-create',['only' => ['store']]);
        $this->middleware('permission:event-category-edit',['only' => ['update']]);
        $this->middleware('permission:event-category-delete',['only' => ['destroy','bulk_action']]);
    }

    public function index(Request $request)
    {
        $all_categories = EventCategory::select('id','title','status')->get();
        return view('event::tenant.backend.events.category')->with([
            'all_categories' => $all_categories,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:event_categories',
            'status' => 'nullable|string|max:191',
        ]);

        $category = new EventCategory();
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
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

        $category =  EventCategory::findOrFail($request->id);
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->status = $request->status;
        $category->save();
        return response()->success(ResponseMessage::SettingsSaved());

    }

    public function destroy($id)
    {
        if (Event::where('category_id',$id)->first()){
            return redirect()->back()->with([
                'msg' => __('You can not delete this category, It is already associated with a post...'),
                'type' => 'danger'
            ]);
        }
        $category =  EventCategory::where('id',$id)->first();
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        EventCategory::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

}
