<?php

namespace Modules\Attributes\Http\Controllers;

use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Entities\SubCategory;
use Modules\Attributes\Http\Requests\ChildCategoryStoreRequest;
use Modules\Attributes\Http\Requests\ChildCategoryUpdateRequest;

class ChildCategoryController extends Controller
{
    private const BASE_PATH = 'attributes::backend.';
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-child-category-list|product-child-category-create|product-child-category-edit|product-child-category-delete', ['only', ['index']]);
        $this->middleware('permission:product-child-category-create', ['only', ['store']]);
        $this->middleware('permission:product-child-category-edit', ['only', ['update']]);
        $this->middleware('permission:product-child-category-delete', ['only', ['destroy', 'bulk_action']]);
    }

    public function index(Request $request): View
    {
        $data['all_category'] = Category::all();
        $data['all_child_category'] = ChildCategory::with("sub_category","category","image","status")->get();
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();

        return view(self::BASE_PATH.'child-category.all', compact('data','default_lang'));
    }

    public function store(ChildCategoryStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $sluggable_text = Str::slug($data['slug'] ?? $data['name']);
        $slug = create_slug($sluggable_text, model_name: 'ChildCategory',is_module: true, module_name: 'Attributes');
        $data['slug'] = $slug;


        $child_subcategory = new ChildCategory();
        $child_subcategory->setTranslation('name',$request->lang,$data['name']);
        $child_subcategory->slug = $data['slug'];
        $child_subcategory->status_id = $data['status_id'];
        $child_subcategory->image_id = $data['image_id'];
        $child_subcategory->category_id = $data['category_id'];
        $child_subcategory->sub_category_id = $data['sub_category_id'];
        $child_subcategory->save();


        return $child_subcategory
            ? back()->with(FlashMsg::create_succeed(__('Product Child-Category')))
            : back()->with(FlashMsg::create_failed(__('Product Child-Category')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ChildCategoryUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ChildCategoryUpdateRequest $request)
    {
        $data = $request->validated();

        $child_subcategory = ChildCategory::findOrFail($request->id);
        if ($child_subcategory->slug != $data['slug'])
        {
            $sluggable_text = Str::slug($data['slug'] ?? $data['name']);
            $new_slug = create_slug($sluggable_text, 'ChildCategory', true, 'Attributes');
            $data['slug'] = $new_slug;
        }

        $child_subcategory->setTranslation('name',$request->lang,$data['name']);
        $child_subcategory->slug = $data['slug'];
        $child_subcategory->status_id = $data['status_id'];
        $child_subcategory->image_id = $data['image_id'];
        $child_subcategory->category_id = $data['category_id'];
        $child_subcategory->sub_category_id = $data['sub_category_id'];
        $child_subcategory->save();

        return $child_subcategory
            ? back()->with(FlashMsg::update_succeed(__('Product Child-Category')))
            : back()->with(FlashMsg::update_failed(__('Product Child-Category')));
    }

    public function destroy(ChildCategory $item): ?bool
    {
        return $item->delete();
    }

    public function bulk_action(Request $request): JsonResponse
    {
        ChildCategory::WhereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function getSubcategoriesOfCategory($id): JsonResponse
    {
        $all_subcategory = ChildCategory::where('category_id', $id)
            ->select("id","name")->get();
        return response()->json($all_subcategory);
    }



    // Trash & Restore
    public function trash(): View
    {
        $all_category = ChildCategory::onlyTrashed()->get();
        return view(self::BASE_PATH.'child-category.trash')->with(['all_child_category' => $all_category]);
    }

    public function trash_restore($id)
    {
        $restored = ChildCategory::onlyTrashed()->findOrFail($id)->restore();

        return $restored
            ? back()->with(FlashMsg::restore_succeed(__('Product Child Category')))
            : back()->with(FlashMsg::restore_failed(__('Product Child Category')));
    }

    public function trash_delete($id)
    {
        $deleted= ChildCategory::onlyTrashed()->findOrFail($id)->forceDelete();

        return $deleted
            ? back()->with(FlashMsg::delete_succeed(__('Product Child Category')))
            : back()->with(FlashMsg::delete_failed(__('Product Child Category')));
    }

    public function trash_bulk_delete(Request $request): JsonResponse
    {
        ChildCategory::onlyTrashed()->WhereIn('id', $request->ids)->forceDelete();
        return response()->json(['status' => 'ok']);
    }
}
