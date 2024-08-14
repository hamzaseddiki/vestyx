<?php

namespace Modules\Product\Http\Traits;

use App\Http\Services\CustomPaginationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductChildCategory;
use Modules\Product\Entities\ProductCreatedBy;
use Modules\Product\Entities\ProductDeliveryOption;
use Modules\Product\Entities\ProductGallery;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use Modules\Product\Entities\ProductInventoryDetailAttribute;
use Modules\Product\Entities\ProductShippingReturnPolicy;
use Modules\Product\Entities\ProductSubCategory;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\ProductUom;
use Str;

trait ProductGlobalTrait {
    private function search($request, $route = 'tenant.admin'): array
    {
        $type = $request->type ?? 'default';
        $multiple_date = $this->is_date_range_multiple();

        // create product model instance
        $all_products = Product::query()->with("brand", "category", "childCategory", "subCategory", "inventory");
        // first I need to check who is currently want to take data
        // run a condition that will check if vendor is currently login then only vendor product will return

        // search product name
        $all_products->when(!empty($request->name) && $request->has("name"), function ($query) use ($request) {
            $query->where("name", "LIKE", "%" . $request->name . "%");
        })->when(!empty($request->tag) && $request->has("tag"), function ($query) use ($request) {// search by using tag
            $query->whereHas("tag", function ($i_query) use ($request) {
                $i_query->where("tag_name", "like", "%" . $request->tag . "%");
            });
        })->when(!empty($request->category) && $request->has("category"), function ($query) use ($request) { // category
            $query->whereHas("category", function ($i_query) use ($request) {
                $i_query->where("name", "like", "%" . $request->category . "%");
            });
        })->when(!empty($request->brand) && $request->has("brand"), function ($query) use ($request) { // Brand
            $query->whereHas("brand", function ($i_query) use ($request) {
                $i_query->where("name", "like", "%" . $request->brand . "%");
            });
        })->when(!empty($request->sub_category) && $request->has("sub_category"), function ($query) use ($request) { // sub category
            $query->whereHas("subCategory", function ($i_query) use ($request) {
                $i_query->where("name", "like", "%" . $request->sub_category . "%");
            });
        })->when(!empty($request->child_category) && $request->has("child_category"), function ($query) use ($request) { // child category
            $query->whereHas("childCategory", function ($i_query) use ($request) {
                $i_query->where("name", "like", "%" . $request->child_category . "%");
            });
        })->when(!empty($request->color) && $request->has("color"), function ($query) use ($request) { // color
            $query->whereHas("color", function ($i_query) use ($request) {
                $i_query->where("name", "like", "%" . $request->color . "%");
            });
        })->when(!empty($request->size) && $request->has("size"), function ($query) use ($request) { // size
            $query->whereHas("size", function ($i_query) use ($request) {
                $i_query->where("name", "like", "%" . $request->size . "%");
            });
        })->when(!empty($request->sku) && $request->has("sku"), function ($query) use ($request) { // sku
            $query->whereHas("inventory", function ($i_query) use ($request) {
                $i_query->where("sku", "like", "%" . $request->sku . "%");
            });
        })->when(!empty($request->delivery_option) && $request->has("delivery_option"), function ($query) use ($request) { // delivery option
            $query->whereHas("productDeliveryOption", function ($i_query) use ($request) {
                $i_query->where("title", "like", "%" . $request->delivery_option . "%");
            });
        })->when(!empty($request->refundable) && $request->has("refundable"), function ($query) use ($request) { // refundable
            $query->where("is_refundable", 1);
        })->when(!empty($request->inventory_warning) && $request->has("inventory_warning"), function ($query) use ($request) { // inventory warning
            $query->where("is_inventory_warn_able", 1);
        })->when(!empty($request->from_price) && $request->has("from_price") && !empty($request->to_price) && $request->has("to_price"), function ($query) use ($request) { // price
            $query->whereBetween("sale_price", [$request->from_price, $request->to_price]);
        })->when($multiple_date[0] && $request->has("date_range"), function ($query) use ($request, $multiple_date) { // Order By
            // make separate to date in a array
            $arr = $multiple_date[1];
            $query->whereBetween("created_at", $arr);
        })->when(!empty($multiple_date[0]) && $request->has("date_range"), function ($query) use ($request, $multiple_date) { // Order By
            // make separate to date in a array
            $date = $multiple_date[1];
            $query->whereDate("created_at", $date);
        })->when(!empty($request->order_by) && $request->has("order_by"), function ($query) use ($request) { // Order By
            $query->orderBy("id", $request->order_by);
        })->when(!$request->has('order'), function ($query) {
            $query->orderBy("id", "DESC");
        });

        $display_item_count = request()->count ?? 10;

        $current_query = request()->all();
        $create_query = http_build_query($current_query);

        return CustomPaginationService::pagination_type($all_products, $display_item_count, "custom", route($route . ".product.search") . '?' . $create_query);
    }

    private function is_date_range_multiple(): array
    {
        $date = explode(" to ", request()->date_range);

        if(count($date) > 1 && !empty(request()->date_range)){
            return [true , $date];
        }

        return [false, request()->date_range];
    }

    private function product_type(): int
    {
        return 1;
    }

    public function ProductData($data): array
    {
        return [
            "name"=>[
                $data['lang'] => $data["name"]
            ],
            "summary" => [
                $data['lang'] => $data["summery"]
            ],
            "description" => [
                $data['lang'] => $data["description"]
            ],
            "slug" => Str::slug($data["slug"] ?? $data["name"]),
            "image_id" => $data["image_id"],
            "price" => $data["price"],
            "sale_price" => $data["sale_price"],
            "cost" => $data["cost"],
            "badge_id" => $data["badge_id"],
            "brand_id" => $data["brand"],
            "product_type" => $this->product_type() ?? 2,
            "min_purchase" => $data["min_purchase"],
            "max_purchase" => $data["max_purchase"],
            "is_inventory_warn_able" => $data["is_inventory_warn_able"] ? 1 : 2,
            "is_refundable" => !empty($data["is_refundable"])
        ];
    }

    public function CloneData($data): array
    {
        return [
            "name"  => $data->getTranslation('name',default_lang()),
            "slug" => create_slug(Str::slug($data->slug ?? $data->name), 'Product', true, 'Product', 'slug'),
            "summary" => $data->getTranslation('summary',default_lang()),
            "description" => $data->getTranslation('description',default_lang()),
            "image_id" => $data->image_id,
            "price" => $data->price,
            "sale_price" => $data->sale_price,
            "cost" => $data->cost,
            "badge_id" => $data->badge_id,
            "brand_id" => $data->brand_id,
            "status_id" => $data->status_id ?? 2,
            "product_type" => $this->product_type() ?? 2,
            "min_purchase" => $data->min_purchase,
            "max_purchase" => $data->max_purchase,
            "is_inventory_warn_able" => $data->is_inventory_warn_able ? 1 : 2,
            "is_refundable" => !empty($data->is_refundable)
        ];
    }

    public function prepareProductInventoryDetailsAndInsert($data, $product_id, $inventory_id, $type="create"): bool
    {
        if($type == "update"){
            // get all product inventory detail id
            $prd_in_detail = ProductInventoryDetail::where([
                "product_id" => $product_id,
                "product_inventory_id" => $inventory_id
            ])->select("id")?->pluck("id")?->toArray();

            // delete product inventory detail attribute
            ProductInventoryDetailAttribute::where([
                "product_id" => $product_id
            ])->whereIn("inventory_details_id", $prd_in_detail)->delete();

            // delete all product inventory detail
            ProductInventoryDetail::where([
                "product_id" => $product_id,
                "product_inventory_id" => $inventory_id
            ])->delete();
        }

        // count item stock for getting array length
        $len = count($data["item_stock_count"] ?? []);
        for($i = 0;$i < $len; $i++){
            $arr = [
                "product_id" => $product_id,
                "product_inventory_id" => $inventory_id,
                "color" => $data["item_color"][$i],
                "size" => $data["item_size"][$i],
                "hash" => "",
                "additional_price" => $data["item_additional_price"][$i] ?? 0,
                "add_cost" => $data["item_extra_cost"][$i] ?? 0,
                "image" => $data["item_image"][$i],
                "stock_count" => $data["item_stock_count"][$i],
                "sold_count" => 0
            ];

            $productDetailId = ProductInventoryDetail::create($arr);
            $detailAttr = [];
            for($j = 0; $j < count($data["item_attribute_name"][$i] ?? []);$j++){
                $detailAttr[] = [
                    "product_id" => $product_id,
                    "inventory_details_id" => $productDetailId->id,
                    "attribute_name" => $data["item_attribute_name"][$i][$j] ?? "",
                    "attribute_value" => $data["item_attribute_value"][$i][$j] ?? ""
                ];
            }

            ProductInventoryDetailAttribute::insert($detailAttr);
        }

        return true;
    }

    private function productCategoryData($data,$id,$arrKey = "category_id",$column = "category_id"): array
    {
        return [
            $arrKey => $data[$column],
            "product_id" => $id
        ];
    }

    private function childCategoryData($data, $id): array
    {
        $cl_category = [];
        foreach ($data["child_category"] as $item) {
            $cl_category[] = ["child_category_id" => $item, "product_id" => $id];
        }

        return $cl_category;
    }

    private function prepareDeliveryOptionData($data, $id): array
    {
        // explode string to array
        $arr = [];
        $exp_delivery_option = $this->separateStringToArray($data["delivery_option"], " , ") ?? [];

        foreach($exp_delivery_option as $option){
            $arr[] = [
                "product_id" => $id,
                "delivery_option_id" => $option
            ];
        }

        return $arr;
    }

    private function prepareProductGalleryData($data, $id): array
    {
        // explode string to array
        $arr = [];
        $galleries = $this->separateStringToArray($data["product_gallery"], "|");

        foreach($galleries as $image){
            $arr[] = [
                "product_id" => $id,
                "image_id" => $image
            ];
        }

        return $arr;
    }

    private function prepareProductTagData($data, $id): array
    {
        // explode string to array
        $arr = [];
        $galleries = $this->separateStringToArray($data["tags"], ",");

        foreach($galleries as $tag){
            $arr[] = [
                "product_id" => $id,
                "tag_name" => $tag
            ];
        }

        return $arr;
    }

    private function prepareInventoryData($data, $id = null): array
    {
        $inventoryStockCount = $data["item_stock_count"];
        $stock_count = array_sum($inventoryStockCount ?? []);

        $arr = [
            "sku" => $data["sku"],
            "stock_count" => $stock_count ? $stock_count : $data["quantity"],
        ];

        return $id ? $arr + ["product_id" => $id] : $arr;
    }

    private function separateStringToArray(string | null $string,string $separator = " , "): array|bool
    {
        if(empty($string)) return [];
        return explode($separator, $string);
    }

    public function prepareMetaData($data): array
    {
        return [
            'title' => $data["general_title"] ?? '',
            'description' => $data["general_description"] ?? '',
            'fb_title' => $data["facebook_title"] ?? '',
            'fb_description' => $data["facebook_description"] ?? '',
            'fb_image' => $data["facebook_image"] ?? '',
            'tw_title' => $data["twitter_title"] ?? '',
            'tw_description' => $data["twitter_description"] ?? '',
            'tw_image' => $data["twitter_image"] ?? ''
        ];
    }

    private function userId(){
        return \Auth::guard("admin")->check() ? \Auth::guard("admin")->user()->id : '';
    }

    private function getGuardName(): string
    {
        return \Auth::guard("admin")->check() ? "admin" : "vendor";
    }


    private function createArrayCreatedBy($product_id, $type){
        $arr = [];

        if($type == 'create'){
            $arr = [
                "product_id" => $product_id,
                "created_by_id" => $this->userId(),
                "guard_name" => $this->getGuardName(),
            ];
        }elseif($type == 'update'){
            $arr = [
                "product_id" => $product_id,
                "updated_by" => $this->userId(),
                "created_by_id" => $this->userId(),
                "guard_name" => empty($this->getGuardName()) ? $this->getGuardName() : 'admin',
            ];
        }elseif($type == 'delete'){
            $arr = [
                "product_id" => $product_id,
                "deleted_by" => $this->userId(),
                "deleted_by_guard" => $this->getGuardName(),
            ];
        }

        return $arr;
    }

    public function createdByUpdatedBy($product_id, $type= "create"){
        return ProductCreatedBy::updateOrCreate(
            [
                "product_id" => $product_id
            ],
            $this->createArrayCreatedBy($product_id, $type)
        );
    }

    private function prepareUomData($data, $product_id): array
    {
        return [
            "product_id" => $product_id,
            "unit_id" => $data["unit_id"],
            "quantity" => $data["uom"]
        ];
    }

    public function updateStatus($productId, $statusId): JsonResponse
    {
        $product = Product::find($productId)->update(["status_id" => $statusId]);
        $this->createdByUpdatedBy($productId, "update");

        $response_status = $product ? ["success" => true, "msg" => __("Successfully updated status")] : ["success" => false,"msg" => __("Failed to update status")];
        return response()->json($response_status)->setStatusCode(200);
    }

    /**
     * @param array $data
     * @param $id
     * @param $product
     * @return bool
     */
    public function insert_product_data(array $data, $id, $product): bool
    {
        $inventory = ProductInventory::create($this->prepareInventoryData($data, $id));
        $inventoryDetail = false;

        if (!empty($product["item_stock_count"][0])) {
            $inventoryDetail = $this->prepareProductInventoryDetailsAndInsert($data, $id, $inventory->id);
        }

        $category = ProductCategory::create($this->productCategoryData($product, $id));
        $subcategory = ProductSubCategory::create($this->productCategoryData($product, $id, "sub_category_id", "sub_category"));
        $childCategory = ProductChildCategory::insert($this->childCategoryData($product, $id));
        $deliveryOptions = ProductDeliveryOption::insert($this->prepareDeliveryOptionData($product, $id));
        $productGallery = ProductGallery::insert($this->prepareProductGalleryData($product, $id));
        $productTag = ProductTag::insert($this->prepareProductTagData($product, $id));
        $productUom = ProductUom::create($this->prepareUomData($data, $id));

        $productPolicy = ProductShippingReturnPolicy::create([
            'product_id' => $id,
            'shipping_return_description' => [
                $data['lang'] ?? default_lang() => $data['policy_description']
            ]
        ]);

        return true;
    }

    protected function productInstance($type): Builder
    {
        $product = Product::query();
        if($type == "edit"){
            $product->with(["product_category","tag","uom","product_sub_category","product_child_category","inventory","delivery_option"]);
        }elseif ($type == "single"){
            $product->with(["category","gallery_images","tag","uom","subCategory","childCategory","image","inventory","delivery_option"]);
        }elseif ($type == "list"){
            $product->with(["category","uom" , "subCategory", "childCategory", "brand", "badge" , "image" , "inventory"]);
        }elseif ($type == "search"){
            $product->with(["category","uom" , "subCategory", "childCategory", "brand", "badge" , "image" , "inventory"]);
        }else{
            $product = Product::query()->with(["category" , "subCategory", "childCategory", "brand", "badge" , "image" , "inventory"]);
        }

        return $product;
    }

    private function get_product($type = "single", $id): Model|Builder|null
    {
        // get product instance
        $product = $this->productInstance($type);
        return $product->with("brand")->where("id",$id)->first();
    }

    public function productStore($data){
        $product_data = self::ProductData($data);
        $slug = $data['slug'] ?? $data['name'];
        $product_data['slug'] = create_slug(Str::slug($slug), 'Product', true, 'Product', 'slug');

        $product = Product::create($product_data);
        $id = $product->id;
        $product->metaData()->updateOrCreate(["metainfoable_id" => $id],$this->prepareMetaData($data));
        // store created by info in product created by table
        $this->createdByUpdatedBy($id);
        return $this->insert_product_data($data, $id, $data);
    }

    public function productUpdate($data, $id){
        $product_data = self::ProductData($data);
        $product = Product::with("uom")->find($id);

        if ($product->slug != $data['slug'])
        {
            $slug = $data['slug'] ?? $data['name'];
            $slug = create_slug(Str::slug($slug), 'Product', true, 'Product', 'slug');
            $product_data['slug'] = $slug;
        }

        $product->update($product_data);
        $product->metaData()->updateOrCreate(["metainfoable_id" => $id],$this->prepareMetaData($data));
        $product?->inventory?->updateOrCreate(["product_id" => $id],$this->prepareInventoryData($data));
        // updated by info in product created by table
        $this->createdByUpdatedBy($id, "update");
        // check item stock count is empty or not
        $inventoryDetail = false;
        if(!empty($data["item_stock_count"][0])){
            $inventoryDetail = $this->prepareProductInventoryDetailsAndInsert($data, $id,$product?->inventory?->id,"update");
        }

        $category = $product?->product_category?->updateOrCreate(["product_id" => $id],$this->productCategoryData($data,$id));
        $subcategory = $product?->product_sub_category?->updateOrCreate(["product_id" => $id],$this->productCategoryData($data,$id,"sub_category_id","sub_category"));

        $product->uom->update($this->prepareUomData($data,$id));

        // delete product child category
        ProductChildCategory::where("product_id", $id)->delete();
        ProductDeliveryOption::where("product_id", $id)->delete();
        ProductGallery::where("product_id", $id)->delete();
        ProductTag::where("product_id", $id)->delete();

        $product->uom?->update($this->prepareUomData($data,$id));
        $childCategory = ProductChildCategory::insert($this->childCategoryData($data, $id));
        $deliveryOptions = ProductDeliveryOption::insert($this->prepareDeliveryOptionData($data,$id));
        $productGallery = ProductGallery::insert($this->prepareProductGalleryData($data,$id));
        $productTag = ProductTag::insert($this->prepareProductTagData($data,$id));

        $productPolicy = ProductShippingReturnPolicy::updateOrCreate(
            ['product_id' => $id],
            [
                'product_id' => $id,
                'shipping_return_description' => [
                    $data['lang'] => $data['policy_description']
                ]
            ]);

        return true;
    }

    public function productClone($id): bool
    {
        $data = array();
        $product = Product::findOrFail($id);
        $product_data = self::CloneData($product);

        $newProduct = $product->create($product_data);
        $id = $newProduct->id;

        $metaData = [];
        if ($product?->metaData != null)
        {
            $metaData = [
                'general_title' => $product?->metaData?->title,
                'general_description' => $product?->metaData?->description,
                'facebook_title' => $product?->metaData?->fb_title,
                'facebook_description' => $product?->metaData?->fb_description,
                'facebook_image' => $product?->metaData?->fb_image,
                'twitter_title' => $product?->metaData?->tw_title,
                'twitter_description' => $product?->metaData?->tw_description,
                'twitter_image' => $product?->metaData?->tw_image,
            ];
        }

        $newProduct->metaData()->create(["metainfoable_id" => $id],$this->prepareMetaData($metaData));
        $this->createdByUpdatedBy($id);

        $data["sku"] = create_slug(optional($product->inventory)->sku, 'ProductInventory', true, 'Product', 'sku');
        $inventoryQuantity = $product?->inventory?->stock_count;

        $product->category_id = optional($product->category)->id;
        $product->sub_category = optional($product->subCategory)->id;
        $product->child_category = current(optional($product->childCategory)->pluck('id'));

        $delivery_option = current(optional($product->delivery_option)->pluck('delivery_option_id'));
        $product->delivery_option = implode(' , ', $delivery_option);

        $product_gallery = current(optional($product->product_gallery)->pluck('image_id'));
        $product->product_gallery = implode('|', $product_gallery);

        $product_tags = current(optional($product->tag)->pluck('tag_name'));
        $product->tags = implode(',', $product_tags);

        $data["unit_id"] = optional($product->uom)->unit_id;
        $data["uom"] = optional($product->uom)->quantity;

        // product attributes
        $data['item_stock_count'] = count(optional($product->inventory)->inventoryDetails);
        $data['item_stock_count']= \Arr::wrap($data['item_stock_count']);
        $quantity = null;
        if ($data['item_stock_count'] > 0)
        {
            $data['item_stock_count'] = array();
            foreach (optional($product->inventory)->inventoryDetails ?? [] as $i => $details)
            {
                $data['item_color'][$i] = $details->color;
                $data['item_size'][$i] = $details->size;
                $data['item_additional_price'][$i] = $details->additional_price;
                $data['item_extra_cost'][$i] = $details->add_cost;
                $data['item_image'][$i] = $details->image;
                $data['item_stock_count'][$i] = $details->stock_count;
                $quantity += $details->stock_count;

                foreach ($details->attribute ?? [] as $j => $attribute)
                {
                    $data['item_attribute_name'][$i][$j] = $attribute->attribute_name;
                    $data['item_attribute_value'][$i][$j] = $attribute->attribute_value;
                }
            }
        }
        $data["quantity"] = !empty($quantity) ? $quantity : $inventoryQuantity;
        $data['policy_description'] = $product?->return_policy?->shipping_return_description;

        return $this->insert_product_data($data, $id, $product);
    }

    protected function destroy($id){
        return Product::find($id)->delete();
    }

    protected function trash_destroy($id){
        $product = Product::onlyTrashed()->findOrFail($id);
        ProductUom::where('product_id', $product->id)->delete();
        ProductTag::where('product_id', $product->id)->delete();
        ProductGallery::where('product_id', $product->id)->delete();
        ProductDeliveryOption::where('product_id', $product->id)->delete();
        ProductChildCategory::where('product_id', $product->id)->delete();
        ProductSubCategory::where('product_id', $product->id)->delete();
        ProductCategory::where('product_id', $product->id)->delete();
        ProductInventoryDetailAttribute::where('product_id', $product->id)->delete();
        ProductInventoryDetail::where('product_id', $product->id)->delete();
        ProductInventory::where('product_id', $product->id)->delete();
        $product->forceDelete();

        return (bool)$product;
    }

    protected function bulk_delete($ids)
    {
        $product = Product::whereIn('id' ,$ids)->delete();
        return (bool)$product;
    }

    protected function trash_bulk_delete($ids)
    {
        try {
            ProductUom::whereIn('product_id', $ids)->delete();
            ProductTag::whereIn('product_id', $ids)->delete();
            ProductGallery::whereIn('product_id', $ids)->delete();
            ProductDeliveryOption::whereIn('product_id', $ids)->delete();
            ProductChildCategory::whereIn('product_id', $ids)->delete();
            ProductSubCategory::whereIn('product_id', $ids)->delete();
            ProductCategory::whereIn('product_id', $ids)->delete();
            ProductInventoryDetailAttribute::whereIn('product_id', $ids)->delete();
            ProductInventoryDetail::whereIn('product_id', $ids)->delete();
            ProductInventory::whereIn('product_id', $ids)->delete();
            $products = Product::onlyTrashed()->whereIn('id',$ids)->forceDelete();
        } catch (\Exception $exception) { return false; }

        return (bool)$products;
    }

    public function updateInventory($data, $id){
        $product = Product::find($id);

        $product?->inventory?->updateOrCreate(["product_id" => $id],$this->prepareInventoryData($data));
        // updated by info in product created by table
        $this->createdByUpdatedBy($id, "update");
        // check item stock count is empty or not
        $inventoryDetail = false;
        if(!empty($data["item_stock_count"][0])){
            $inventoryDetail = $this->prepareProductInventoryDetailsAndInsert($data, $id,$product?->inventory?->id,"update");
        }

        return true;
    }
}
