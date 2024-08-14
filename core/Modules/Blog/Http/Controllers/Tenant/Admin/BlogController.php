<?php

namespace Modules\Blog\Http\Controllers\Tenant\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\DataTableHelpers\General;
use App\Helpers\LanguageHelper;
use App\Helpers\ResponseMessage;
use App\Http\Requests\BlogInsertRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Models\MetaInfo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Blog\Actions\Blog\BlogAction;
use Modules\Blog\Actions\Blog\ServiceAction;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\BlogComment;
use Yajra\DataTables\DataTables;

class BlogController extends Controller
{

    private const BASE_PATH = 'blog::tenant.admin.blog.';

    public function __construct()
    {

        $this->middleware('auth:admin');
        $this->middleware('permission:blog-list|blog-edit|blog-delete',['only' => ['index']]);
        $this->middleware('permission:blog-create',['only' => ['new_blog','store_new_blog']]);
        $this->middleware('permission:blog-edit',['only' => ['clone_blog','edit_blog','update_blog']]);
        $this->middleware('permission:blog-delete',['only' => ['delete_blog','bulk_action_blog','delete_blog_all_lang']]);
        $this->middleware('permission:blog-settings',['only' => ['blog_single_page_settings','update_blog_single_page_settings']]);
        $this->middleware('permission:page-settings-blog-page-manage',['only' => ['blog_area','update_blog_area']]);
    }


    public function index(Request $request){

        $default_lang = $request->lang ?? GlobalLanguage::default_slug();

        if ($request->ajax()){

            $data = Blog::usingLocale($default_lang)->select('*')->orderBy('id','desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('checkbox',function ($row){
                    return General::bulkCheckbox($row->id);
                })

                ->addColumn('author',function ($row){
                    return $row->author_data()->name ?? __('Anonymous');
                })

                ->addColumn('views',function ($row){
                    return $row->views ?? 0 ;
                })

                ->addColumn('title',function ($row) use($default_lang){
                    return $row->getTranslation('title',$default_lang);
                })

                ->addColumn('image',function ($row) use($default_lang) {
                    return General::image($row->image);
                })

                ->addColumn('category_id',function ($row) use($default_lang ){
                    return '<span class="badge badge-primary">'.optional($row->category)->getTranslation('title',$default_lang).'</span>';
                })
                ->addColumn('status',function ($row){
                    return General::statusSpan($row->status);
                })

                ->addColumn('date',function ($row){
                    return date_format($row->created_at,'d-M-Y');
                })


                ->addColumn('action', function($row)use($default_lang){
                    $admin = auth()->guard('admin')->user();
                    $action = '';
                    if($admin->can('blog-delete')):
                        $action .= General::deletePopover(route(route_prefix().'admin.blog.delete.all.lang',$row->id));
                    endif;
                    if($admin->can('blog-edit')):
                        $action .= General::editIcon(route(route_prefix().'admin.blog.edit',$row->id).'?lang='.$default_lang);
                        $action .= General::cloneIcon(route(route_prefix().'admin.blog.clone'),$row->id);
                     endif;
                        $single_route = route('tenant.frontend.blog.single',$row->slug);
                        $action .= General::viewIcon($single_route);
                        $action .= '<br>';
                        $action .= General::viewComments(route(route_prefix().'admin.blog.comments.view',$row->id),$row->id);
                    return $action;
                })
                ->rawColumns(['action','checkbox','image','status','category_id','title'])
                ->make(true);
        }

        return view(self::BASE_PATH.'index',compact('default_lang'));
    }

    public function new_blog(Request $request){

        $all_category = BlogCategory::where('status',1)->get();

        return view(self::BASE_PATH.'blog-new')->with([
            'all_category' => $all_category,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }
    public function store_new_blog(BlogInsertRequest $request, BlogAction $blogAction)
    {
        if(tenant()){
            $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
            $pages_count = Blog::count();
            $permission_page = @$current_package->blog_permission_feature;

            if(!empty($permission_page) && $pages_count >= $permission_page){
                return response()->danger(ResponseMessage::delete(sprintf('You can not create blog above %d in this package',$permission_page)));
            }
        }

        $blogAction->store_execute($request);
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function edit_blog(Request $request,$id){

        if(!empty($id)){
            $blog_post = Blog::find($id);
             $all_category = BlogCategory::select(['id','title'])->get();
        }

        return view(self::BASE_PATH.'blog-edit')->with([
            'all_category' => $all_category,
            'blog_post' => $blog_post,
            'default_lang' => $request->lang ?? LanguageHelper::default_slug(),
        ]);
    }

    public function update_blog(BlogUpdateRequest $request, BlogAction $blogAction, $id)
    {
        $blogAction->update_execute($request,$id);
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete_blog_all_lang($id){
        $blog = Blog::find($id);
        $blog->metainfo()->delete();
        $blog->delete();
        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action_blog(Request $request){
         Blog::whereIn('id',$request->ids)->delete();
         MetaInfo::whereIn('metainfoable_id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function clone_blog(Request $request, BlogAction $blogAction)
    {
        if(tenant()) {
            $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
            $permission_page = $current_package->blog_permission_feature;
            $pages_count = Blog::count();
            if (!empty($permission_page) && $pages_count >= $permission_page) {
                return response()->danger(ResponseMessage::delete(sprintf('You can not create blog above %d in this package', $permission_page)));
            }
        }

        $blogAction->clone_blog_execute($request);
        return response()->success(ResponseMessage::SettingsSaved('Blog Cloned Successfylly..'));
    }


    public function view_comments($id)
    {
        $blog_comments = Blog::find($id);

        return view(self::BASE_PATH.'comments',compact('blog_comments'));
    }

    public function delete_all_comments(Request $request,$id){
        $category =  BlogComment::where('id',$id)->first();
        $category->delete();
        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action_comments(Request $request){
        BlogComment::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function blog_settings()
    {
        return view(self::BASE_PATH.'settings');
    }

    public function update_blog_settings(Request $request)
    {
            $request->validate([
                'category_page_item_show' => 'nullable|string',
                'tag_page_item_show' => 'nullable|string',
                'search_page_item_show' => 'nullable|string',
                'blog_avater_image' => 'nullable|string',
            ]);
            $fields = [
                'category_page_item_show',
                'tag_page_item_show',
                'search_page_item_show',
                'blog_avater_image',
            ];
            foreach ($fields as $item) {
              if($request->has($item)){
               update_static_option($item, $request->$item);
            }

        }
        return response()->success(ResponseMessage::SettingsSaved());
    }


}
