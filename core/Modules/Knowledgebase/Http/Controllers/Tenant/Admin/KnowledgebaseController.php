<?php

namespace Modules\Knowledgebase\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Models\Language;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Donation\Helpers\DataTableHelpers\SubAppointmentGeneral;
use Modules\Event\Actions\Event\EventAdminAction;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Modules\Event\Entities\EventComment;
use Modules\Event\Entities\EventPaymentLog;
use Modules\Event\Helpers\DataTableHelpers\EventDatatable;
use Modules\Event\Helpers\DataTableHelpers\EventGeneral;
use Modules\Event\Http\Requests\EventRequest;
use Modules\Job\Actions\Job\JobAdminAction;
use Modules\Job\Entities\Job;
use Modules\Job\Entities\JobCategory;
use Modules\Job\Entities\JobPaymentLog;
use Modules\Job\Helpers\DataTableHelpers\JobDatatable;
use Modules\Job\Helpers\DataTableHelpers\JobGeneral;
use Modules\Job\Http\Requests\JobRequest;
use Modules\Knowledgebase\Actions\Knowledgebase\KnowledgebaseAdminAction;
use Modules\Knowledgebase\Entities\Knowledgebase;
use Modules\Knowledgebase\Entities\KnowledgebaseCategory;
use Modules\Knowledgebase\Helpers\DataTableHelpers\KnowledgebaseDatatable;
use Modules\Knowledgebase\Helpers\DataTableHelpers\KnowledgebaseGeneral;
use Modules\Knowledgebase\Http\Requests\KnowledgebaseRequest;
use Yajra\DataTables\DataTables;


class KnowledgebaseController extends Controller
{

    private const BASE_PATH = 'knowledgebase::tenant.backend.knowledgebases.';

    public function __construct()
    {
        $this->middleware('permission:knowledgebase-list|knowledgebase-create|knowledgebase-edit|knowledgebase-delete',['only' => 'index']);
        $this->middleware('permission:knowledgebase-create',['only' => 'create','store']);
        $this->middleware('permission:knowledgebase-edit',['only' => 'edit','update','clone']);
        $this->middleware('permission:knowledgebase-delete',['only' => 'delete','bulk_action']);
    }

    public function index(Request $request)
    {
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        if ($request->ajax()){
            $data = Knowledgebase::usingLocale($default_lang)->select('*')->orderBy('id','desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('checkbox',function ($row){
                    return KnowledgebaseGeneral::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row) use ($default_lang){
                    return KnowledgebaseDatatable::infoColumn($row,$default_lang);
                })
                ->addColumn('image',function ($row){
                    return KnowledgebaseGeneral::image($row->image);
                })
                ->addColumn('category',function ($row) use($default_lang){
                    return optional($row->category)->getTranslation('title',$default_lang);
                })
                ->addColumn('status',function($row){
                   return KnowledgebaseGeneral::statusSpan($row->status);
                })
                ->addColumn('action', function($row){
                    $action = '';
                    $route = route('tenant.frontend.knowledgebase.single',$row->slug);
                    $action.= KnowledgebaseGeneral::viewIcon($route);
                    $admin = auth()->guard('admin')->user();

                    if ($admin->can('knowledgebase-edit')){
                        $action .= KnowledgebaseGeneral::editIcon(route('tenant.admin.knowledgebase.edit',$row->id));
                        $action .= KnowledgebaseGeneral::cloneIcon(route('tenant.admin.knowledgebase.clone'),$row->id);
                    }
                    if ($admin->can('knowledgebase-delete')){
                        $action .= KnowledgebaseGeneral::deletePopover(route('tenant.admin.knowledgebase.delete',$row->id));
                    }

                    return $action;
                })
                ->rawColumns(['checkbox','image','info','action','status'])
                ->make(true);
        }
        return view(self::BASE_PATH . 'all-knowledgebase',compact('default_lang'));
    }

    public function create(Request $request)
    {
        $all_category = KnowledgebaseCategory::select('id','title','status')->where(['status' => 1])->get();
        return view(self::BASE_PATH . 'new-knowledgebase')->with([
            'all_category' => $all_category,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function store(KnowledgebaseRequest $request, KnowledgebaseAdminAction $action)
    {
        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
        $pages_count = Knowledgebase::count();
        $permission_page = $current_package->job_permission_feature;
            if(!empty($permission_page) && $pages_count >= $permission_page){
                return response()->danger(ResponseMessage::delete(sprintf('You can not create article above %d in this package',$permission_page)));
            }
        $response = $action->store_execute($request);
        return redirect()->back()->with($response);
    }

    public function edit($id)
    {
        $knowledgebase = Knowledgebase::findOrFail($id);
        $all_category = KnowledgebaseCategory::select('id','title')->get();
        return view(self::BASE_PATH . 'edit-knowledgebase')->with(['knowledgebase' => $knowledgebase, 'all_category' => $all_category]);
    }

    public function update(KnowledgebaseRequest $request, $id, KnowledgebaseAdminAction $action)
    {
        $response = $action->update_execute($request,$id);
        return redirect()->back()->with($response);
    }

    public function clone(Request $request, KnowledgebaseAdminAction $action)
    {
        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
        $pages_count = Knowledgebase::count();
        $permission_page = $current_package->job_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create job above %d in this package',$permission_page)));
        }

        $response = $action->clone_execute($request);
        return redirect()->back()->with($response);
    }

    public function delete($id)
    {
        $data = Knowledgebase::findOrFail($id);
        if(!empty($data->metainfo)){
            $data->metainfo()->delete();
        }

        $old_files = json_decode($data->files) ?? [];
        foreach ($old_files as $file){
            if(file_exists('assets/uploads/article-files/'.$file) && !is_dir('assets/uploads/article-files/'.$file)){
                unlink('assets/uploads/article-files/'.$file);
            }
        }

        $data->delete();
        return redirect()->back()->with(['msg' => __('Job Deleted...'), 'type' => 'danger']);
    }

    public function bulk_action(Request $request)
    {
        $logs = Knowledgebase::find($request->ids);

        foreach ($logs as $data){
            if(!empty($data->metainfo)){
                $data->metainfo()->delete();
            }

            $old_files = json_decode($data->files) ?? [];
            foreach ($old_files as $file){
                if(file_exists('assets/uploads/article-files/'.$file) && !is_dir('assets/uploads/article-files/'.$file)){
                    unlink('assets/uploads/article-files/'.$file);
                }
            }

            $data->delete();
        }
        return response()->json(['status' => 'ok']);
    }



}
