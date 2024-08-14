<?php

namespace Modules\Inventory\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Attributes\Entities\Brand;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\DeliveryOption;
use Modules\Attributes\Entities\ProductBrand;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\Tag;
use Modules\Attributes\Entities\Unit;
use Modules\Badge\Entities\Badge;
use Modules\Inventory\Http\Requests\UpdateInventoryRequest;
use Modules\Inventory\Http\Services\Backend\InventoryServices;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductSize;
use Modules\Product\Http\Services\Admin\AdminProductServices;

class InventoryController extends Controller
{
    const BASE_URL = 'inventory::backend.';

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-inventory-list|product-inventory-create|product-inventory-edit|product-inventory-delete', ['only', ['index']]);
        $this->middleware('permission:product-inventory-create', ['only', ['create', 'store']]);
        $this->middleware('permission:product-inventory-edit', ['only', ['edit', 'update']]);
        $this->middleware('permission:product-inventory-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_inventory_products = ProductInventory::with('product')->get();
        return view(self::BASE_URL.'all', compact('all_inventory_products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_products = Product::all();
        $all_attributes = ProductAttribute::select('id', 'title', 'terms')->get();
        return view(self::BASE_URL.'new')->with([
            'all_products' => $all_products,
            'all_attributes' => $all_attributes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string|unique:product_inventories,sku',
            'stock_count' => 'nullable|numeric',
            'inventory_details' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $product_inventory = ProductInventory::create([
                'product_id' => $request->sanitize_html('product_id'),
                'sku' => 'SKU-' . $request->sanitize_html('sku'),
                'stock_count' => $request->sanitize_html('stock_count'),
            ]);

            if ($request->inventory_details && count($request->inventory_details)) {
                $this->insertInventoryDetails($product_inventory->id, $request->inventory_details);
            }

            DB::commit();
            return response()->json(FlashMsg::create_succeed(__('Product Inventory')));
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(FlashMsg::create_failed(__('Product Inventory')), 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product\ProductInventory  $productInventory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductInventory $item)
    {
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
        ];

        $inventory = $item->where('id', $item->id)->with('inventoryDetails')->first();
        $all_products = Product::all();
        $all_attribute = ProductAttribute::all()->groupBy('title')->map(fn($query) => $query[0]);
        $product_colors = Color::all();
        $product_sizes = Size::all();
        $product = (new AdminProductServices)->get_edit_product($item->product_id);

        return view(self::BASE_URL.'edit')->with([
            'inventory' => $inventory,
            'all_products' => $all_products,
            'all_attributes' => $all_attribute,
            'product_colors' => $product_colors,
            'product_sizes' => $product_sizes,
            'data'=>$data,
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product\ProductInventory  $productInventory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInventoryRequest $request)
    {
        try {
            Db::beginTransaction();

            (new InventoryServices)->update($request->validated());

            DB::commit();
            return response()->json(FlashMsg::update_succeed(__('Product Inventory')));
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(FlashMsg::update_failed(__('Product Inventory')), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return bool
     */
    public function destroy(Request $request)
    {
        return (bool) ProductInventoryDetails::find($request->id)->delete();
    }

    public function bulk_action(Request $request)
    {
        $deleted = ProductInventory::whereIn('id', $request->ids)->delete();
        if ($deleted) {
            back()->with(FlashMsg::delete_succeed(__('Product Inventory')));
        }
        return back()->with(FlashMsg::delete_failed(__('Product Inventory')));
    }

    public function settings()
    {
        return view(self::BASE_URL.'settings');
    }

    public function settings_update(Request $request)
    {
        $fields = $request->validate([
            'stock_threshold_amount' => 'required|numeric',
            'stock_warning_message' => 'nullable'
        ]);

        foreach ($fields as $field => $value)
        {
            update_static_option($field, $value);
        }

        return back()->with(FlashMsg::update_succeed('Inventory Settings'));
    }

    /** =========================================================================
     *                          HELPER FUNCTIONS
    ========================================================================= */
    private function insertInventoryDetails($inventory_id, $inventory_details)
    {
        foreach ($inventory_details as $details) {
            $product_inventory_details = ProductInventoryDetails::create([
                'inventory_id' => $inventory_id,
                'attribute_id' => $details['attribute_id'],
                'attribute_value' => $details['attribute_value'],
                'stock_count' => $details['stock_count'],
            ]);
        }
        return true;
    }

    private function deleteAllDetailsOfInventory($inventory_id)
    {
        return (bool) ProductInventoryDetails::where('inventory_id', $inventory_id)->delete();
    }
}
