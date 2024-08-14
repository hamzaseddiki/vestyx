<?php

namespace Modules\Donation\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Donation\Actions\Donation\DonationActivityAction;
use Modules\Donation\Entities\DonationActivity;
use Modules\Donation\Entities\DonationActivityCategory;
use Modules\Donation\Http\Requests\DonationActivityRequest;

class DonationActivityController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:donation-activity-list|donation-activity-create|donation-activity-edit|donation-activity-delete',['only' => ['index']]);
        $this->middleware('permission:donation-activity-create',['only' => ['create','store']]);
        $this->middleware('permission:donation-activity-edit',['only' => ['edit','update']]);
        $this->middleware('permission:donation-activity-delete',['only' => ['bulk_action','delete']]);
    }

    public function index(Request $request)
    {
        $all_activities = DonationActivity::with('category')->select('id','title','slug','status','image','category_id')->get();
        return view('donation::tenant.backend.donations.activity.all-activities')->with([
            'all_activities' => $all_activities,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function create(Request $request)
    {
        $all_categories = DonationActivityCategory::select('id','title','slug')->get();
        return view('donation::tenant.backend.donations.activity.new-activities')->with([
            'all_categories' => $all_categories,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function store(DonationActivityRequest $request, DonationActivityAction $action)
    {
        $action->store_execute($request);
        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function edit($id, Request $request)
    {
        $activity = DonationActivity::findOrFail($id);
        $all_categories = DonationActivityCategory::select('id','title','slug')->get();
        return view('donation::tenant.backend.donations.activity.edit-activities')->with([
            'activity' => $activity,
            'all_categories' => $all_categories,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }


    public function update(DonationActivityRequest $request, DonationActivityAction $action, $id)
    {
        $action->update_execute($request,$id);
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete($id)
    {
        $data = DonationActivity::findOrFail($id);
        if(!empty($data->metainfo)){
            $data->metainfo()->delete();
        }
        $data->delete();
        return redirect()->back()->with(['msg' => __('Donation Deleted...'), 'type' => 'danger']);
    }

    public function bulk_action(Request $request)
    {
        $logs = DonationActivity::find($request->ids);

        foreach ($logs as $data){
            if(!empty($data->metainfo)){
                $data->metainfo()->delete();
            }
            $data->delete();
        }
        return response()->json(['status' => 'ok']);
    }
}
