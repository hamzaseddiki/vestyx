<?php

namespace Modules\Service\Http\Controllers\Tenant\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Service\Actions\Service\ServiceAction;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\ServiceCategory;
use Modules\Service\Http\Requests\ServiceRequest;
use function view;

class ServiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:service-list|service-create|service-edit|service-delete',['only' => ['index']]);
        $this->middleware('permission:service-create',['only' => ['add','store']]);
        $this->middleware('permission:service-edit',['only' => ['edit_service','update_service']]);
        $this->middleware('permission:service-delete',['only' => ['delete','bulk_action_service']]);
    }

    public function index(Request $request){
        $all_services = Service::select('id','category_id','title','slug','status','created_at','image')->get();

        return view('service::tenant.admin.services.service-index')->with([
            'all_services' => $all_services,
            'default_lang'=> $request->lang ?? GlobalLanguage::default_slug()
        ]);
    }

    public function add(Request $request){

        $categories = ServiceCategory::select('id','title')->get();
        return view('service::tenant.admin.services.service-add')->with([
            'categories' => $categories,
            'default_lang'=> $request->lang ?? GlobalLanguage::default_slug()
        ]);
    }

    public function store(ServiceRequest $request, ServiceAction $action)
    {

        if(tenant()) {
            $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
            $pages_count = Service::count();
            $permission_page = $current_package->service_permission_feature;

            if(!empty($permission_page) && $pages_count >= $permission_page){
                return response()->danger(ResponseMessage::delete(sprintf('You can not create service above %d in this package',$permission_page)));
            }
        }

        $response = $action->store_execute($request);
        return redirect()->back()->with($response);
    }

    public function edit_service(Request $request,$id){

        if(!empty($id)){
            $service = Service::find($id);
        }
        $categories = ServiceCategory::select('id','title')->get();
        return view('service::tenant.admin.services.service-edit')->with([
            'service' => $service,
            'categories' => $categories,
            'default_lang'=> $request->lang ?? GlobalLanguage::default_slug()
        ]);
    }

    public function update_service(ServiceRequest $request, ServiceAction $action)
    {
        $response = $action->update_execute($request,$request->id);
        return redirect()->back()->with($response);
    }

    public function delete(Request $request,$id){
        $data = Service::findOrFail($id);
        if(!empty($data->metainfo)){
            $data->metainfo()->delete();
        }
        $data->delete();

        return response()->danger(ResponseMessage::delete('Service Deleted'));
    }

    public function bulk_action_service(Request $request){

        $logs = Service::find($request->ids);

        foreach ($logs as $data){
            if(!empty($data->metainfo)){
                $data->metainfo()->delete();
            }
            $data->delete();
        }

        return response()->json(['status' => 'ok']);
    }
}
