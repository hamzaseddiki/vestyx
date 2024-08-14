<?php

namespace Modules\Attributes\Http\Controllers;

use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use App\Models\Status;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Http\Requests\StoreCategoryRequest;
use Modules\Attributes\Http\Requests\UpdateCategoryRequest;
use Modules\Product\Entities\ProductCategory;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-category-list|product-category-create|product-category-edit|product-category-delete', ['only', ['index']]);
        $this->middleware('permission:product-category-create', ['only', ['store']]);
        $this->middleware('permission:product-category-edit', ['only', ['update']]);
        $this->middleware('permission:product-category-delete', ['only', ['destroy', 'bulk_action']]);
    }


    public function index(Request $request): View|Factory|Application
    {
        $all_status = \App\Models\Status::all();
        $all_category = Category::with(["image:id,path", "status"])->get();
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();

        return view('attributes::backend.category.all')->with([
            'all_category' => $all_category,
            'all_status'=>$all_status,
            'default_lang' => $default_lang
        ]);
    }


    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $sluggable_text = $data['slug'] == null ? Str::slug(trim($data['name'])) : Str::slug($data['slug']);
        $slug = create_slug($sluggable_text, model_name: 'category',is_module: true, module_name: 'attributes');
        $data['slug'] = $slug;

        $category = new Category();
        $category->setTranslation('name',$request->lang,$data['name']);
        $category->slug = $data['slug'];
        $category->description = $data['description'];
        $category->status_id = $data['status_id'];
        $category->image_id = $data['image_id'];
        $category->save();

        return $category
            ? back()->with(FlashMsg::create_succeed(__('Product Category')))
            : back()->with(FlashMsg::create_failed(__('Product Category')));
    }

    public function offer_with_message(Request $request)
    {
        $offer_with_message = JobRequest::select('id','seller_id','buyer_id')->with('chatMessages')->where('id',$request->offer_id)->first();
        // job seller request with img
        $message_image_link = [];
        foreach ($offer_with_message->chatMessages as $job_req_message){
            $img_get = get_attachment_image_by_id($job_req_message->image);
            $message_image_link[] = $img_get['img_url'];
        }
        $message_image = $message_image_link;
        return response()->json([
            'message'=> $offer_with_message,
        ]);
    }


    public function update(UpdateCategoryRequest $request)
    {
        $data = $request->validated();

        $category = Category::find($request->id);
        if ($category->slug != $data['slug'])
        {
            $sluggable_text = Str::slug($data['slug'] ?? $data['name']);
            $new_slug = create_slug($sluggable_text, 'category', true, 'attributes');
            $data['slug'] = $new_slug;
        }

        $category->setTranslation('name',$request->lang,$data['name']);
        $category->slug = $data['slug'];
        $category->description = $data['description'];
        $category->status_id = $data['status_id'];
        $category->image_id = $data['image_id'];
        $category->save();


        return $category
            ? back()->with(FlashMsg::update_succeed(__('Product Category')))
            : back()->with(FlashMsg::update_failed(__('Product Category')));
    }


    public function destroy(Category $item): ?bool
    {
        return $item->delete();
    }

    public function bulk_action(Request $request): JsonResponse
    {
        Category::WhereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }



    public function trash(): View
    {
        $all_category = Category::onlyTrashed()->get();
        return view('attributes::backend.category.trash')->with(['all_category' => $all_category]);
    }

    public function trash_restore($id)
    {
        $restored = Category::onlyTrashed()->findOrFail($id)->restore();

        return $restored
            ? back()->with(FlashMsg::restore_succeed(__('Product Category')))
            : back()->with(FlashMsg::restore_failed(__('Product Category')));
    }

    public function trash_delete($id)
    {
        $deleted= Category::onlyTrashed()->findOrFail($id)->forceDelete();

        return $deleted
            ? back()->with(FlashMsg::delete_succeed(__('Product Category')))
            : back()->with(FlashMsg::delete_failed(__('Product Category')));
    }

    public function trash_bulk_delete(Request $request): JsonResponse
    {
        Category::onlyTrashed()->WhereIn('id', $request->ids)->forceDelete();
        return response()->json(['status' => 'ok']);
    }
}
