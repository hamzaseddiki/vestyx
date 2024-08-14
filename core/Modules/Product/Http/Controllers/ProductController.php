<?php

namespace Modules\Product\Http\Controllers;

use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Mail\ProductOrderEmail;
use App\Mail\StockOutEmail;
use App\Models\Status;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\Attributes\Entities\Brand;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\DeliveryOption;
use Modules\Attributes\Entities\ProductBrand;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\SubCategory;
use Modules\Attributes\Entities\Tag;
use Modules\Attributes\Entities\Unit;
use Modules\Badge\Entities\Badge;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductChildCategory;
use Modules\Product\Entities\ProductDeliveryOption;
use Modules\Product\Entities\ProductGallery;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use Modules\Product\Entities\ProductInventoryDetailAttribute;
use Modules\Product\Entities\ProductSize;
use Modules\Product\Entities\ProductSubCategory;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\ProductUom;
use Modules\Product\Http\Requests\ProductStoreRequest;
use Modules\Product\Http\Services\Admin\AdminProductServices;
use Stripe\Service\ProductService;

class ProductController extends Controller
{
    CONST BASE_PATH = '';

    public function __construct(){
        $this->middleware("auth:admin");
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $products = AdminProductServices::productSearch($request);
        $statuses = Status::all();
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        return view('product::index',compact("products","statuses","default_lang"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        $data = [
            "brands" => ProductBrand::select("id", "name")->get(),
            "badges" => Badge::where("status","active")->get(),
            "units" => Unit::select("id", "name")->get(),
            "tags" => Tag::select("id", "tag_text as name")->get(),
            "categories" => Category::select("id", "name")->get(),
            "deliveryOptions" => DeliveryOption::select("id", "title", "sub_title", "icon")->get(),
            "all_attribute" => ProductAttribute::all()->groupBy('title')->map(fn($query) => $query[0]),
            "product_colors" => Color::all(),
            "product_sizes" => Size::all(),
            "default_lang" => $default_lang,
        ];

        return view('product::create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     * @param ProductStoreRequest $request
     * @return JsonResponse
     */

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        return response()->json((new AdminProductServices)->store($data) ? ["success" => true] : ["success" => false]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $id, $aria_name = null)
    {
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        $data = [
            "brands" => ProductBrand::select("id", "name")->get(),
            "badges" => Badge::where("status","active")->get(),
            "units" => Unit::select("id", "name")->get(),
            "tags" => Tag::select("id", "tag_text as name")->get(),
            "categories" => Category::select("id", "name")->get(),
            "deliveryOptions" => DeliveryOption::select("id", "title", "sub_title", "icon")->get(),
            "all_attribute" => ProductAttribute::all()->groupBy('title')->map(fn($query) => $query[0]),
            "product_colors" => Color::all(),
            "product_sizes" => Size::all(),
            'aria_name' => $aria_name,
            'default_lang' => $default_lang
        ];

        $product = (new AdminProductServices)->get_edit_product($id);
        $subCat = $product?->subCategory?->id ?? null;
        $cat = $product?->category?->id ?? null;

        $sub_categories = SubCategory::select("id", "name")->where("category_id", $cat)->where("status_id", 1)->get();
        $child_categories = ChildCategory::select("id", "name")->where("sub_category_id", $subCat)->where("status_id", 1)->get();

        return view('product::edit', compact("data", "product", "sub_categories", "child_categories"));
    }

    public function update(ProductStoreRequest $request, $id)
    {
        $data = $request->validated();
        return response()->json((new AdminProductServices)->update($data, $id) ? ["success" => true] : ["success" => false]);
    }

    private function validateUpdateStatus($req): array
    {
        return Validator::make($req,[
            "id" => "required",
            "status_id" => "required"
        ])->validated();
    }

    public function update_status(Request $request)
    {
        $data = $this->validateUpdateStatus($request->all());
        return (new AdminProductServices)->updateStatus($data["id"],$data["status_id"]);
    }

    public function clone($id)
    {
        return (new AdminProductServices)->clone($id) ? back()->with(FlashMsg::clone_succeed('Product')) : back()->with(FlashMsg::clone_failed('Product'));
    }

    public function destroy(int $id): JsonResponse
    {
        return response()->json((new AdminProductServices)->delete($id) ? ["success" => true, "msg" => "Product deleted successfully"] : ["success" => false]);
    }

    public function bulk_destroy(Request $request): JsonResponse
    {
        return response()->json((new AdminProductServices)->bulk_delete_action($request->ids) ? ["success" => true] : ["success" => false]);
    }

    public function trash(): Renderable
    {
        $products = Product::with('category','subCategory', 'childCategory')->onlyTrashed()->get();
        return view('product::trash',compact("products"));
    }

    public function restore($id)
    {
        $restore = Product::onlyTrashed()->findOrFail($id)->restore();
        return back()->with($restore ? FlashMsg::restore_succeed('Trashed Product') : FlashMsg::restore_failed('Trashed Product'));
    }

    public function trash_delete($id)
    {
        return (new AdminProductServices)->trash_delete($id) ? back()->with(FlashMsg::delete_succeed('Trashed Product')) : back()->with(FlashMsg::delete_failed('Trashed Product'));
    }

    public function trash_bulk_destroy(Request $request)
    {
        return response()->json((new AdminProductServices)->trash_bulk_delete_action($request->ids) ? ["success" => true] : ["success" => false]);
    }

    public function trash_empty(Request $request)
    {
        $ids = explode('|', $request->ids);
        return response()->json((new AdminProductServices)->trash_bulk_delete_action($ids) ? ["success" => true] : ["success" => false]);
    }

    public function productSearch(Request $request): string
    {
        $products = AdminProductServices::productSearch($request);
        $statuses = Status::all();
        $default_lang = $request->lang ?? (new LanguageHelper)->default_slug();

        return view('product::search',compact("products","statuses","default_lang"))->render();
    }
}
