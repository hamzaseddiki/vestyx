<?php

namespace Modules\Portfolio\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Portfolio\Actions\Portfolio\PortfolioAdminAction;
use Modules\Portfolio\Entities\Portfolio;
use Modules\Portfolio\Entities\PortfolioCategory;
use Modules\Portfolio\Helpers\DataTableHelpers\PortfolioGeneral;
use Modules\Portfolio\Http\Requests\PortfolioRequest;
use Yajra\DataTables\DataTables;


class PortfolioController extends Controller
{

    private const BASE_PATH = 'portfolio::tenant.backend.portfolio.';

    public function __construct()
    {
        $this->middleware('permission:portfolio-list|portfolio-create|portfolio-edit|portfolio-delete',['only' => 'index']);
        $this->middleware('permission:portfolio-create',['only' => 'create','store']);
        $this->middleware('permission:portfolio-edit',['only' => 'edit','update','clone']);
        $this->middleware('permission:portfolio-delete',['only' => 'delete','bulk_action']);
    }

    public function index(Request $request)
    {
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();

        if ($request->ajax()){
            $data = Portfolio::usingLocale($default_lang)->select('*')->orderBy('id','desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return PortfolioGeneral::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row) use ($default_lang){
                    return \Modules\Portfolio\Helpers\DataTableHelpers\PortfolioDatatable::infoColumn($row,$default_lang);
                })
                ->addColumn('image',function ($row){
                    return PortfolioGeneral::image($row->image);
                })
                ->addColumn('category',function ($row) use($default_lang){
                    return optional($row->category)->getTranslation('title',$default_lang);
                })
                ->addColumn('status',function($row){
                   return PortfolioGeneral::statusSpan($row->status);
                })
                ->addColumn('action', function($row){
                    $action = '';
                   $action.= PortfolioGeneral::viewIcon(route('tenant.frontend.portfolio.single',$row->slug));
                    $admin = auth()->guard('admin')->user();

                    if ($admin->can('portfolio-edit')){
                        $action .= PortfolioGeneral::editIcon(route('tenant.admin.portfolio.edit',$row->id));
                        $action .= PortfolioGeneral::cloneIcon(route('tenant.admin.portfolio.clone'),$row->id);
                    }

                    if ($admin->can('portfolio-delete')){
                        $action .= PortfolioGeneral::deletePopover(route('tenant.admin.portfolio.delete',$row->id));
                    }
                    return $action;
                })
                ->rawColumns(['checkbox','image','info','action','status'])
                ->make(true);
        }
        return view(self::BASE_PATH . 'index',compact('default_lang'));

    }

    public function create(Request $request)
    {
        $all_category = PortfolioCategory::select('id','title','status')->where(['status' => 1])->get();
        return view(self::BASE_PATH . 'new-portfolio')->with([
            'all_category' => $all_category,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function store(PortfolioRequest $request, PortfolioAdminAction $action)
    {
        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
        $pages_count = Portfolio::count();
        $permission_page = $current_package->portfolio_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create portfolio above %d in this package',$permission_page)));
        }
        $response = $action->store_execute($request);
        return redirect()->back()->with($response);
    }

    public function edit($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $all_category = PortfolioCategory::select('id','title')->get();
        return view(self::BASE_PATH . 'edit-portfolio')->with(['portfolio' => $portfolio, 'all_category' => $all_category]);
    }

    public function update(PortfolioRequest $request, $id, PortfolioAdminAction $action)
    {
        $response = $action->update_execute($request,$id);
        return redirect()->back()->with($response);
    }

    public function delete($id)
    {
        $data = Portfolio::findOrFail($id);
        if(!empty($data->metainfo)){
          $data->metainfo()->delete();
        }
        $data->delete();
        return redirect()->back()->with(['msg' => __('Portfolio Deleted..'), 'type' => 'danger']);
    }

    public function clone(Request $request, PortfolioAdminAction $action)
    {
        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
        $pages_count = Portfolio::count();
        $permission_page = $current_package->portfolio_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create portfolio above %d in this package',$permission_page)));
        }
        $action->clone_execute($request);
        return redirect()->back()->with(['msg' => __('Portfolio Cloned Successfully..'), 'type' => 'success']);
    }

    public function bulk_action(Request $request)
    {
        $logs = Portfolio::find($request->ids);

            foreach ($logs as $data){
                if(!empty($data->metainfo)){
                  $data->metainfo()->delete();
                }
                $data->delete();
            }
        return response()->json(['status' => 'ok']);
    }
}
