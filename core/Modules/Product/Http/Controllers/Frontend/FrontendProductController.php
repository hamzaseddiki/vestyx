<?php

namespace Modules\Product\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Services\CheckoutCouponService;
use App\Models\StaticOption;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\SubCategory;
use Modules\Campaign\Entities\Campaign;
use Modules\Campaign\Entities\CampaignSoldProduct;
use Modules\CountryManage\Entities\State;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductChildCategory;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use Modules\Product\Entities\ProductInventoryDetailAttribute;
use Modules\Product\Entities\ProductReviews;
use Modules\Product\Entities\ProductSubCategory;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\ProductUom;
use Modules\ShippingModule\Entities\ShippingMethod;
use Modules\ShippingModule\Entities\ZoneRegion;
use Modules\TaxModule\Entities\CountryTax;
use Modules\TaxModule\Entities\StateTax;

class FrontendProductController extends Controller
{
    private const BASE_PATH_OLD = 'product::frontend.shop.';
    private const BASE_PATH = 'shop.';
    public function shop_page(Request $request)
    {
        $product_object = Product::with('badge', 'campaign_product')
            ->where('status_id', 1);

        if ($request->has('category')) {
            $category = $request->category;
            $product_object->whereHas('category', function ($query) use ($category) {
                return $query->where('slug', $category);
            });
        }

        if ($request->has('size')) {
            $size = $request->size;
            $product_object->whereHas('inventoryDetail', function ($query) use ($size) {
                return $query->where('size', $size);
            });
        }

        if ($request->has('color')) {
            $color = $request->color;
            $product_object->whereHas('inventoryDetail', function ($query) use ($color) {
                return $query->where('color', $color);
            });
        }

        if ($request->has('tag')) {
            $tag = $request->tag;
            $product_object->whereHas('tag', function ($query) use ($tag) {
                return $query->where('tag_name', 'LIKE', "%{$tag}%");
            });
        }

        if ($request->has('min_price') && $request->has('max_price')) {
            $min_price = $request->min_price;
            $max_price = $request->max_price;
            $product_object->whereBetween('sale_price', [$min_price, $max_price]);
        }

        if ($request->has('rating')) {
            $rating = $request->rating;

            $product_object->whereHas('reviews', function ($query) use ($rating) {
                $query->where('rating', $rating);
            });
        }

        if ($request->has('sort')) {

            $order = 'desc';
            switch ($request->sort) {
                case 1:
                    $order_by = 'name';
                    break;
                case 2:
                    $order_by = 'rating';
                    break;
                case 3:
                    $order_by = 'created_at';
                    break;
                case 4:
                    $order_by = 'sale_price';
                    $order = 'asc';
                    break;
                case 5:
                    $order_by = 'sale_price';
                    break;
                default:
                    $order_by = 'created_at';
            }

            $product_object->orderBy($order_by, $order);
        } else {
            $product_object->latest();
        }

        $product_object = $product_object->paginate(12)->withQueryString();

        $create_arr = $request->all();
        $create_url = http_build_query($create_arr);


        $product_object->url(route('tenant.shop') . '?' . $create_url);
        $product_object->url(route('tenant.shop') . '?' . $create_url);

        $links = $product_object->getUrlRange(1, $product_object->lastPage());
        $current_page = $product_object->currentPage();

        $products = $product_object->items();

        $grid = themeView(self::BASE_PATH."partials.product-partials.grid-products", compact("products", "links", "current_page"))->render();
        $list = themeView(self::BASE_PATH."partials.product-partials.list-products", compact("products", "links", "current_page"))->render();
        return response()->json(["list" => $list, "grid" => $grid, 'pagination' => $product_object]);
    }

    public function shop_search(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ]);

        $search = $request->search;

        $product_object = Product::with('badge', 'campaign_product')
            ->where('status_id', 1)
            ->where("name", "LIKE", "%" . $search . "%")
            ->orWhere("sale_price", $search);

        $product_object = $product_object->paginate(12)->withQueryString();

        $create_arr = $request->all();
        $create_url = http_build_query($create_arr);


        $product_object->url(route('tenant.shop') . '?' . $create_url);
        $product_object->url(route('tenant.shop') . '?' . $create_url);

        $links = $product_object->getUrlRange(1, $product_object->lastPage());
        $current_page = $product_object->currentPage();

        $products = $product_object->items();

        $grid = themeView(self::BASE_PATH."partials.product-partials.grid-products", compact("products", "links", "current_page"))->render();
        $list = themeView(self::BASE_PATH."partials.product-partials.list-products", compact("products", "links", "current_page"))->render();


        if ($request->ajax()) {
            return response()->json(["list" => $list, "grid" => $grid, 'pagination' => $product_object]);
        }

        return themeView(self::BASE_PATH.'product-single-search', compact('product_object', 'search'));
    }

    public function product_quick_view(Request $request)
    {
        $product = Product::with('badge', 'campaign_product')->findOrFail($request->id);
        $modal_object = themeView(self::BASE_PATH.'markup_for_controller.quick_view_product_modal', compact('product'))->render();

        return response()->json(['product_modal' => $modal_object]);
    }

    public function product_details($slug)
    {
      $data =   extract($this->productVariant($slug));

        $sub_category_arr = json_decode($product->sub_category_id, true);

        // related products
        $product_category = $product?->category?->id;
        $product_id = $product->id;
        $related_products = Product::where('status_id', 1)
            ->whereIn('id', function ($query) use ($product_id, $product_category) {
                $query->select('product_categories.product_id')
                ->from(with(new ProductCategory())->getTable())
                ->where('product_id', '!=', $product_id)
                ->where('category_id', '=', $product_category)
                ->get();
            })
            ->inRandomOrder()
            ->take(5)
            ->get();


        // sidebar data
        $all_category = ProductCategory::all();
        $all_units = ProductUom::all();
        $maximum_available_price = Product::query()->with('category')->max('price');
        $min_price = request()->pr_min ? request()->pr_min : Product::query()->min('price');
        $max_price = request()->pr_max ? request()->pr_max : $maximum_available_price;
        $all_tags = ProductTag::all();

        return themeView(self::BASE_PATH.'product_details.product-details', compact(
            'product',
            'related_products',
            'available_attributes',
            'product_inventory_set',
            'additional_info_store',
            'all_category',
            'all_units',
            'maximum_available_price',
            'min_price',
            'max_price',
            'all_tags',
            'productColors',
            'productSizes',
            'setting_text',
        ));
    }

    public function add_to_cart(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'pid_id' => 'nullable',
            'product_variant' => 'nullable',
            'selected_size' => 'nullable',
            'selected_color' => 'nullable'
        ]);

        $product_inventory = ProductInventory::where('product_id', $request->product_id)->first();
        if ($request->product_variant) {
            $product_inventory_details = ProductInventoryDetail::where('id', $request->product_variant)->first();
        } else {
            $product_inventory_details = null;
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        }

        if (!empty($product->campaign_product)) {
            $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();
            $product = Product::where('id', $request->product_id)->first();

            $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;
        } else {
            $product_left = $product_inventory_details->stock_count ?? $product_inventory->stock_count;
        }

        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be cart. Campaign product stock limit is over!')
            ]);
        }

        try {
            $cart_data = $request->all();
            $product = Product::findOrFail($cart_data['product_id']);

            // check product minimum and maximum quantity
            if(!is_null($product->min_purchase) && $request->quantity < $product->min_purchase){
                return response()->json([
                    'type' => 'error',
                    'error_msg' =>  __("This product is required minimum quantity of $product->min_purchase")
                ]);
            }
            if(!is_null($product->max_purchase) && $request->quantity > $product->max_purchase){
                return response()->json([
                    "type" => "error",
                    "error_msg" => __("This product is allowed you to add maximum quantity of $product->max_purchase")
                ]);
            }

            $sale_price = $product->sale_price;
            $additional_price = 0;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image_id;
            if ($cart_data['product_variant']) {
                $size_name = Size::find($cart_data['selected_size'])->name ?? '';
                $color_name = Color::find($cart_data['selected_color'])->name ?? '';

                $product_detail = ProductInventoryDetail::where("id", $cart_data['product_variant'])->first();

                $product_attributes = $product_detail->attribute?->pluck('attribute_value', 'attribute_name', 'inventory_details')
                    ->toArray();

                $options = [
                    'variant_id' => $request->product_variant,
                    'color_name' => $color_name,
                    'size_name' => $size_name,
                    'attributes' => $product_attributes,
                    'image' => $product_detail->image ?? $product_image
                ];
            } else {
                $options = ['image' => $product_image];
            }

            $category = $product?->category?->id;
            $subcategory = $product?->subCategory?->id ?? null;

            $options['used_categories'] = [
                'category' => $category,
                'subcategory' => $subcategory
            ];

            Cart::instance("default")->add(['id' => $cart_data['product_id'], 'name' => $product->getTranslation("name", get_user_lang()), 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to cart'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!')
            ]);
        }
    }


    public function add_to_wishlist(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'pid_id' => 'nullable',
            'product_variant' => 'nullable',
            'selected_size' => 'nullable',
            'selected_color' => 'nullable'
        ]);

        $product_inventory = ProductInventory::where('product_id', $request->product_id)->first();
        if ($request->product_variant) {
            $product_inventory_details = ProductInventoryDetail::where('id', $request->product_variant)->first();
        } else {
            $product_inventory_details = null;
        }

        if (!Auth::guard("web")->check()) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('If you want to add cart item into wishlist than you have to login first.')
            ]);
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        }

        if (!empty($product->campaign_product)) {
            $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();
            $product = Product::where('id', $request->product_id)->first();

            $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;
        } else {
            $product_left = $product_inventory_details->stock_count ?? $product_inventory->stock_count;
        }


        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be cart. Campaign product stock limit is over!')
            ]);
        }

        DB::beginTransaction();
        try {
            $cart_data = $request->all();
            $product = Product::findOrFail($cart_data['product_id']);

            $sale_price = $product->sale_price;
            $additional_price = 0;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image_id;
            if ($cart_data['product_variant']) {
                $size_name = Size::find($cart_data['selected_size'])->name ?? '';
                $color_name = Color::find($cart_data['selected_color'])->name ?? '';

                $product_detail = ProductInventoryDetail::where("id", $cart_data['product_variant'])->first();

                $product_attributes = $product_detail->attribute?->pluck('attribute_value', 'attribute_name', 'inventory_details')
                    ->toArray();

                $options = [
                    'variant_id' => $request->product_variant,
                    'color_name' => $color_name,
                    'size_name' => $size_name,
                    'attributes' => $product_attributes,
                    'image' => $product_detail->image ?? $product_image
                ];
            } else {
                $options = ['image' => $product_image];
            }

            $category = $product?->category?->id;
            $subcategory = $product?->subCategory?->id ?? null;

            $options['used_categories'] = [
                'category' => $category,
                'subcategory' => $subcategory
            ];
            $username = Auth::guard("web")->user()->id;

            Cart::instance("wishlist")->restore($username);
            Cart::instance("wishlist")->add(['id' => $cart_data['product_id'], 'name' => $product->getTranslation("name", get_user_lang()), 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);
            Cart::instance("wishlist")->store($username);

            DB::commit();

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to wishlist'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!')
            ]);
        }
    }


    public function add_to_compare(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'pid_id' => 'nullable',
            'product_variant' => 'nullable',
            'selected_size' => 'nullable',
            'selected_color' => 'nullable'
        ]);

        $product_inventory = ProductInventory::where('product_id', $request->product_id)->first();
        if ($request->product_variant) {
            $product_inventory_details = ProductInventoryDetail::where('id', $request->product_variant)->first();
        } else {
            $product_inventory_details = null;
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        }

        if (!empty($product->campaign_product)) {
            $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();
            $product = Product::where('id', $request->product_id)->first();

            $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;
        } else {
            $product_left = $product_inventory_details->stock_count ?? $product_inventory->stock_count;
        }


        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be compare. Campaign product stock limit is over!')
            ]);
        }

        DB::beginTransaction();
        try {
            $cart_data = $request->all();
            $product = Product::findOrFail($cart_data['product_id']);

            $sale_price = $product->sale_price;
            $additional_price = 0;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image_id;
            if ($cart_data['product_variant']) {
                $size_name = Size::find($cart_data['selected_size'])->name ?? '';
                $color_name = Color::find($cart_data['selected_color'])->name ?? '';

                $product_detail = ProductInventoryDetail::where("id", $cart_data['product_variant'])->first();

                $product_attributes = $product_detail->attribute?->pluck('attribute_value', 'attribute_name', 'inventory_details')
                    ->toArray();

                $options = [
                    'variant_id' => $request->product_variant,
                    'color_name' => $color_name,
                    'size_name' => $size_name,
                    'attributes' => $product_attributes,
                    'description' => $product->description,
                    'sku' => $product?->inventory?->sku,
                    'image' => $product_detail->image ?? $product_image
                ];
            } else {
                $options = ['image' => $product_image];
            }

            $category = $product?->category?->id;
            $subcategory = $product?->subCategory?->id ?? null;

            $options['used_categories'] = [
                'category' => $category,
                'subcategory' => $subcategory
            ];

            Cart::instance("compare")->add(['id' => $cart_data['product_id'], 'name' => $product->getTranslation("name", get_user_lang()), 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);

            DB::commit();

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to compare.'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!')
            ]);
        }
    }

    public function cart_page()
    {
        $cart_data = Cart::instance("default")->content();
        $wishlist = false;
        return themeView(self::BASE_PATH.'cart.cart_page', compact('cart_data', 'wishlist'));
    }

    public function wishlist_page()
    {
       if(!\auth()->check()){
           return back();
       }
        $username = Auth::guard("web")->user()->id;

        Cart::instance("wishlist")->restore($username);
        $cart_data = Cart::instance("wishlist")->content();
        Cart::instance("wishlist")->store($username);
        $wishlist = true;

        return themeView(self::BASE_PATH.'cart.cart_page', compact('cart_data', 'wishlist'));
    }

    public function cart_update_ajax(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'variant_id' => 'nullable'
        ]);

        $product_data = Cart::get($request->product_id);
        $product_inventory = ProductInventory::where('product_id', $product_data->id)->first();
        $product_inventory_details = ProductInventoryDetail::where('id', $request->variant_id)->first();

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        }

        $sold_count = CampaignSoldProduct::where('product_id', $product_data->id)->first();
        $product = Product::where('id', $product_data->id)->first();

        $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;

        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be cart. Campaign product stock limit is over!')
            ]);
        }


        DB::beginTransaction();
        try {
            $rowId = $request->product_id;
            $qty = max($request->quantity, 1);

            Cart::update($rowId, ['qty' => $qty]);
            DB::commit();

            $cart_data = Cart::content();
            $markup = themeView(self::BASE_PATH.'cart.markup_for_controller.cart_products', compact('cart_data'))->render();
            //$cart_price_markup = view('product::frontend.shop.cart.markup_for_controller.cart_price', compact('cart_data'))->render();

            return response()->json([
                'type' => 'success',
                'msg' => 'Cart is updated',
                'markup' => $markup,
                'cart_price_markup' => $cart_price_markup ?? '',
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!')
            ]);
        }
    }

    public function cart_clear_ajax()
    {
        Cart::destroy();
        return back();
    }

    public function cart_remove_product_ajax(Request $request): JsonResponse
    {
        $request->validate([
            'product_hash_id' => 'required'
        ]);

        Cart::remove($request->product_hash_id);


        return response()->json([
            'type' => 'success',
            'msg' => 'The product is removed from the cart',
            'empty_cart' => Cart::count() == 0 ? themeView(self::BASE_PATH.'cart.cart_empty')->render() : ''
        ]);
    }

    public function wishlist_remove_product_ajax(Request $request): JsonResponse
    {
        $request->validate([
            'product_hash_id' => 'required'
        ]);

        $username = Auth::guard("web")->user()->id;

        Cart::instance("wishlist")->restore($username);

        Cart::instance("wishlist")->remove($request->product_hash_id);

        Cart::instance("wishlist")->store($username);


        return response()->json([
            'type' => 'success',
            'msg' => 'The product is removed from the cart',
            'empty_cart' => Cart::count() == 0 ? themeView(self::BASE_PATH.'cart.cart_empty')->render() : ''
        ]);
    }

    public function wishlist_move_product_ajax(Request $request): JsonResponse
    {
        $request->validate([
            'product_hash_id' => 'required'
        ]);

        $username = Auth::guard("web")->user()->id;

        Cart::instance("wishlist")->restore($username);

        $cartItem = Cart::instance("wishlist")->get($request->product_hash_id);

        Cart::instance("wishlist")->remove($request->product_hash_id);

        Cart::instance("default")->add(['id' => (int)$cartItem->id, 'name' => $cartItem->name, 'qty' => $cartItem->qty, 'price' => $cartItem->price, 'weight' => '0', 'options' => [
            "variant_id" => $cartItem->options->variant_id ?? "",
            "color_name" => $cartItem->options->color_name ?? "",
            "size_name" => $cartItem->options->size_name ?? "",
            "image" => $cartItem->options->image ?? "",
            "attributes" => $cartItem->options->attributes ?? "",
            "used_categories" => $cartItem->options->used_categories ?? "",
        ]]);

        Cart::instance("wishlist")->store($username);

        return response()->json([
            'type' => 'success',
            'msg' => 'The product is removed from the cart',
            'empty_cart' => Cart::count() == 0 ? themeView(self::BASE_PATH.'cart.cart_empty')->render() : ''
        ]);
    }

    public function buy_now(Request $request)
    {

        $request->validate([
            'pid_id' => 'nullable',
            'product_id' => 'required',
            'quantity' => 'required',
            'product_variant' => 'nullable',
            'selected_size' => 'nullable',
            'selected_color' => 'nullable'
        ]);

        $product_inventory = ProductInventory::where('product_id', $request->product_id)->first();
        if ($request->product_variant) {
            $product_inventory_details = ProductInventoryDetail::where('id', $request->product_variant)->first();
        } else {
            $product_inventory_details = null;
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        }

        $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();
        $product = Product::where('id', $request->product_id)->first();
        $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;

        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be cart. Campaign product stock limit is over!')
            ]);
        }

        DB::beginTransaction();
        try {
            $cart_data = $request->all();
            $product = Product::findOrFail($cart_data['product_id']);

            // check product minimum and maximum quantity
            if(!is_null($product->min_purchase) && $request->quantity < $product->min_purchase){
                return response()->json([
                    "type" => "error",
                    "error_msg" => __("This product is required minimum quantity of $product->min_purchase")
                ]);
            }
            if(!is_null($product->max_purchase) && $request->quantity > $product->max_purchase){
                return response()->json([
                    "type" => "error",
                    "error_msg" => __("This product is allowed you to add maximum quantity of $product->max_purchase")
                ]);
            }

            $sale_price = $product->sale_price;
            $additional_price = 0;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image_id;
            if ($cart_data['product_variant']) {
                $size_name = Size::find($cart_data['selected_size'])->name ?? '';
                $color_name = Color::find($cart_data['selected_color'])->name ?? '';

                $product_attributes = ProductInventoryDetailAttribute::where('inventory_details_id', $cart_data['product_variant'])
                    ->select('attribute_name', 'attribute_value')
                    ->get()
                    ->pluck('attribute_value', 'attribute_name')
                    ->toArray();

                $options = [
                    'variant_id' => $request->product_variant,
                    'color_name' => $color_name,
                    'size_name' => $size_name,
                    'attributes' => $product_attributes,
                    'image' => $product_image
                ];
            } else {
                $options = ['image' => $product_image];
            }

            $category = $product?->category?->id;
            $subcategory = $product?->subCategory?->id ?? null;

            $options['used_categories'] = [
                'category' => $category,
                'subcategory' => $subcategory
            ];

            Cart::add(['id' => $cart_data['product_id'], 'name' => $product->getTranslation("name", get_user_lang()), 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);
            DB::commit();

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to cart',
                'redirect' => route('tenant.shop.checkout')
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!')
            ]);
        }
    }

    public function get_state_ajax(Request $request) // Syncs with product shipping and tax rate
    {
        $request->validate([
            'country' => 'required'
        ]);

        $states = State::where('country_id', $request->country)->get();

        $markup = '<option value="">' . __('Select a state') . '</option>';
        foreach ($states as $state) {
            $markup .= '<option value="' . $state->id . '">' . $state->name . '</option>';
        }

        return response()->json([
            'type' => 'success',
            'markup' => $markup,
        ]);
    }

    public function sync_product_total(Request $request)
    {
        $request->validate([
            'country' => 'required',
            'state' => 'nullable',
            'coupon' => 'nullable'
        ]);

        $country = $request->country;
        $state = $request->state;

        // test code
        $all_cart_items = Cart::content();
        $products = Product::with("category", "subCategory", "childCategory")->whereIn('id', $all_cart_items?->pluck("id")?->toArray())->get();
        $subtotal = Cart::subtotal();

        $coupon_amount_total = CheckoutCouponService::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');

        $product_tax = $this->get_product_shipping_tax($request);
        $shipping_zones = ZoneRegion::whereJsonContains('country', $request->country)->get();
        $zone_ids = [];
        foreach ($shipping_zones ?? [] as $zone) {
            $zone_ids[] = $zone->zone_id;
        }

        $shipping_methods = ShippingMethod::whereIn('zone_id', $zone_ids)
            ->orWhere('is_default', 1)->get();
        $default_shipping = $shipping_methods->where('is_default', 1)->first();

        $shipping_tax_markup = view(self::BASE_PATH_OLD.'checkout.markup_for_controller.shipping_tax_ajax', compact('product_tax', 'shipping_methods', 'default_shipping', 'country', 'state','coupon_amount_total'))->render();

        return response()->json([
            'type' => 'success',
            'sync_price_total_markup' => $shipping_tax_markup,
            'coupon_amount' => float_amount_with_currency_symbol($coupon_amount_total)
        ]);
    }

    public function sync_product_total_wth_shipping_method(Request $request)
    {
        $request->validate([
            'country' => 'required',
            'state' => 'nullable',
            'shipping_method' => 'required',
            'total' => 'required',
            'coupon' => 'nullable'
        ]);

        // test code
        $all_cart_items = Cart::content();
        $products = Product::with("category", "subCategory", "childCategory")->whereIn('id', $all_cart_items?->pluck("id")?->toArray())->get();
        $subtotal = Cart::subtotal();

        $coupon_amount_total = CheckoutCouponService::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');

        $selected_shipping_method = ShippingMethod::with('options')->find($request->shipping_method);
        $shipping_charge = $selected_shipping_method?->options?->cost;

        if ($subtotal < $selected_shipping_method?->options?->minimum_order_amount) {
            return response()->json([
                'type' => 'error',
                'msg' => site_currency_symbol() . $selected_shipping_method?->options?->minimum_order_amount . ' ' . __('Minimum order amount needed.')
            ]);
        }

        $subtotal -= $coupon_amount_total;

        if ($selected_shipping_method?->options?->tax_status == 1) {
            $country_tax = CountryTax::where('country_id', $request->country)->select('tax_percentage')->first();
            if ($request->state !== null) {
                $state_tax = StateTax::where(['country_id' => $request->country, 'state_id' => $request->state])->select('tax_percentage')->first();
            }

            $tax = isset($state_tax) && $state_tax != null ? $state_tax : $country_tax;
            $taxed_shipping_charge = $tax != null ? (($tax->tax_percentage / 100) * $shipping_charge) : $shipping_charge;
            $total = $subtotal + $taxed_shipping_charge + $shipping_charge;
        } else {
            $total = $subtotal + $shipping_charge;
        }

        return response()->json([
            'type' => 'success',
            'selected_shipping_method' => $selected_shipping_method,
            'total' => round($total, 2),
            'coupon_amount' => float_amount_with_currency_symbol(round($coupon_amount_total,2) ?? 0)
        ]);
    }

    /** ==============================================================================
     *                              Checkout Page Coupon
     * ============================================================================== */
    public function sync_product_coupon(Request $request)
    {
        $request->validate([
            'country' => 'nullable',
            'state' => 'nullable',
            'coupon' => 'required',
        ]);

        $country = $request->country;
        $state = $request->state;

        // test code
        $all_cart_items = Cart::content();
        $products = Product::with("category", "subCategory", "childCategory")->whereIn('id', $all_cart_items?->pluck("id")?->toArray())->get();
        $subtotal = Cart::subtotal();

        $coupon_amount_total = CheckoutCouponService::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');

        $product_tax = $this->get_product_shipping_tax($request);
        $shipping_zones = ZoneRegion::whereJsonContains('country', $request->country)->get();
        $zone_ids = [];
        foreach ($shipping_zones ?? [] as $zone) {
            $zone_ids[] = $zone->zone_id;
        }


        $shipping_methods = ShippingMethod::whereIn('zone_id', $zone_ids)
            ->orWhere('is_default', 1)->get();
        $default_shipping = $shipping_methods->where('is_default', 1)->first();

        $shipping_tax_markup = themeView(self::BASE_PATH.'checkout.markup_for_controller.shipping_tax_ajax', compact('product_tax', 'shipping_methods', 'default_shipping', 'country', 'state','coupon_amount_total'))->render();

        $coupon_type = $coupon_amount_total > 0 ? 'success' : 'invalid';
        return response()->json([
            'type' => $coupon_type,
            'sync_price_total_markup' => $shipping_tax_markup,
            'coupon_amount' => float_amount_with_currency_symbol($coupon_amount_total),
            'success' => $coupon_amount_total > 0
        ]);
    }

    private function get_product_shipping_tax_for_coupon($request)
    {
        $shipping_cost = 0;
        $product_tax = 0;

        if ($request['state'] && $request['country']) {
            $product_tax = StateTax::where(['country_id' => $request['country'], 'state_id' => $request['state']])->select('id', 'tax_percentage')->first();
            if ($product_tax) {
                $product_tax = $product_tax->toArray()['tax_percentage'];
            }
        } else {
            $product_tax = CountryTax::where('country_id', $request['country'])->select('id', 'tax_percentage')->first();
            if ($product_tax) {
                $product_tax = $product_tax->toArray()['tax_percentage'];
            }
        }

        $shipping = ShippingMethod::find($request['shipping_method']);
        $shipping_option = $shipping->options ?? null;

        if ($shipping_option != null && $shipping_option?->tax_status == 1) {
            $shipping_cost = $shipping_option?->cost + (($shipping_option?->cost * $product_tax) / 100);
        } else {
            $shipping_cost = $shipping_option?->cost;
        }

        $data['product_tax'] = $product_tax ?? 0;
        $data['shipping_cost'] = $shipping_cost ?? 0;

        return $data;
    }

    private function get_product_shipping_tax($request)
    {
        $product_tax = 0;
        $country_tax = CountryTax::where('country_id', $request->country)->select('id', 'tax_percentage')->first();

        if ($request->state && $request->country) {
            $product_tax = StateTax::where(['country_id' => $request->country, 'state_id' => $request->state])
                ->select('id', 'tax_percentage')->first();

            if (!empty($product_tax)) {
                $product_tax = $product_tax->toArray()['tax_percentage'];
            } else {
                if (!empty($country_tax))
                {
                    $product_tax = $country_tax->toArray()['tax_percentage'];
                }
            }
        } else {
            $product_tax = $country_tax?->toArray()['tax_percentage'];
        }

        return $product_tax;
    }

    public function product_review(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required',
            'review_text' => 'required|max:1000'
        ]);

        $user = Auth::guard('web')->user();
        $existing_record = ProductReviews::where(['user_id' => $user->id, 'product_id' => $request->product_id])->select('id')->first();

        if (!$existing_record) {
            $product_review = new ProductReviews();
            $product_review->user_id = $user->id;
            $product_review->product_id = $request->product_id;
            $product_review->rating = $request->rating;
            $product_review->review_text = trim($request->review_text);
            $product_review->save();

            return response()->json(['type' => 'success', 'msg' => 'Your review is submitted']);
        }

        return response()->json(['type' => 'danger', 'msg' => 'Your have already submitted review on this product']);
    }

    public function render_reviews(Request $request)
    {
        $reviews = ProductReviews::with('user')->where('product_id', $request->product_id)->orderBy('created_at', 'desc')->take($request->items)->get();
        $review_markup = themeView(self::BASE_PATH.'product_details.markup_for_controller.product_reviews', compact('reviews'))->render();

        return response()->json([
            'type' => 'success',
            'markup' => $review_markup
        ]);
    }

    public function wishlist_product(Request $request)
    {

        $wishlist = ProductWishlist::where(['product_id' => $request->product_id, 'user_id' => Auth::guard('web')->user()->id])->first();

        if ($wishlist) {
            $wishlist->delete();
            $msg = 'Product is removed from wishlist';
        } else {
            ProductWishlist::create([
                'user_id' => Auth::guard('web')->user()->id,
                'product_id' => $request->product_id
            ]);
            $msg = 'Product added in wishlist successfully';
        }

        return response()->json(['type' => 'success', 'msg' => $msg]);
    }

    public function compare_product_page(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $session_array = Session::get('products');

        $product_array = [];
        foreach ($session_array ?? [] as $key => $item) {
            $product_array[$key] = Product::with('reviews', 'color', 'sizes', 'inventory')->findOrFail($item);
        }

        return themeView(self::BASE_PATH.'product-compare', compact('product_array'));
    }

    public function compare_product(Request $request)
    {
        $request->validate([
            'products' => 'required'
        ]);

        $products = explode(',', $request->products);

        foreach ($products as $key => $product) {
            Product::findOrFail($product);
        }

        Session::put('products', $products);

        return response()->json([
            'type' => 'success',
            'msg' => __('Product added in comparison board'),
            'item_count' => count($products),
            'url' => route('tenant.shop.compare.product.page')
        ]);
    }

    public function compare_product_remove(Request $request): bool
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        Cart::instance("compare")->remove($request->product_id);

        return true;
    }


    private function productVariant($slug)
    {
        $product = Product::where('slug', $slug)
            ->with([
                'category',
                'tag',
                'color',
                'sizes',
                'inventoryDetail',
                'inventoryDetail.productColor',
                'inventoryDetail.productSize',
                'inventoryDetail.attribute',
                'reviews',
                'campaign_product' => function ($query){
                    $query->whereDate('start_date','<=', now()->format("Y-m-d"));
                    $query->whereDate('end_date','>=', now()->format("Y-m-d"));
                }
            ])
            ->where("status_id", 1)
            ->firstOrFail();

        // get selected attributes in this product ( $available_attributes )
        $inventoryDetails = optional($product->inventoryDetail);
        $product_inventory_attributes = $inventoryDetails->toArray();



        $all_included_attributes = array_filter(array_column($product_inventory_attributes, 'attribute', 'id'));
        $all_included_attributes_prd_id = array_keys($all_included_attributes);

        $available_attributes = [];  // FRONTEND : All displaying attributes
        $product_inventory_set = []; // FRONTEND : attribute store
        $additional_info_store = []; // FRONTEND : $additional info_store


        foreach ($all_included_attributes as $id => $included_attributes) {

            $single_inventory_item = [];
            foreach ($included_attributes as $included_attribute_single) {
                $available_attributes[$included_attribute_single['attribute_name']][$included_attribute_single['attribute_value']] = 1;

                // individual inventory item
                $single_inventory_item[$included_attribute_single['attribute_name']] = $included_attribute_single['attribute_value'];
                if (optional($inventoryDetails->find($id))->productColor) {
                    $single_inventory_item['Color'] = optional(optional($inventoryDetails->find($id))->productColor)->name;
                }

                if (optional($inventoryDetails->find($id))->productSize) {
                    $single_inventory_item['Size'] = optional($inventoryDetails->find($id)->productSize)->getTranslation('name', get_user_lang());
                }
            }

            $item_additional_price = optional(optional($product->inventoryDetail)->find($id))->additional_price ?? 0;
            $item_additional_stock = optional(optional($product->inventoryDetail)->find($id))->stock_count ?? 0;
            $image = get_attachment_image_by_id(optional(optional($product->inventoryDetail)->find($id))->image)['img_url'] ?? '';

            $product_inventory_set[] = $single_inventory_item;

            $sorted_inventory_item = $single_inventory_item;
            ksort($sorted_inventory_item);

            $additional_info_store[md5(json_encode($sorted_inventory_item))] = [
                'pid_id' => $id, //Info: ProductInventoryDetails id
                'additional_price' => $item_additional_price,
                'stock_count' => $item_additional_stock,
                'image' => $image,
            ];
        }

        $productColors = $product->color->unique();
        $productSizes = $product->sizes->unique();

        $colorAndSizes = $product?->inventoryDetail?->whereNotIn("id", $all_included_attributes_prd_id);


        if (!empty($colorAndSizes)) {
            $product_id = $product_inventory_attributes[0]['id'] ?? $product->id;

            foreach ($colorAndSizes as $inventory) {

                // if this inventory has attributes then it will fire continue statement
                if (in_array($inventory->product_id, $all_included_attributes_prd_id)) {
                    continue;
                }

                $single_inventory_item = [];

                if (optional($inventoryDetails->find($product_id))->color) {
                    $single_inventory_item['Color'] = optional($inventory->productColor)->getTranslation('name', get_user_lang());
                }

                if (optional($inventoryDetails->find($product_id))->size) {
                    $single_inventory_item['Size'] = optional($inventory->productSize)->getTranslation('name', get_user_lang());
                }

                $product_inventory_set[] = $single_inventory_item;

                $item_additional_price = optional($inventory)->additional_price ?? 0;
                $item_additional_stock = optional($inventory)->stock_count ?? 0;
                $image = get_attachment_image_by_id(optional($inventory)->image)['img_url'] ?? '';

                $sorted_inventory_item = $single_inventory_item;

                ksort($sorted_inventory_item);

                $additional_info_store[md5(json_encode($sorted_inventory_item))] = [
                    'pid_id' => $product_id,
                    'additional_price' => $item_additional_price,
                    'stock_count' => $item_additional_stock,
                    'image' => $image,
                ];
            }
        }

        $available_attributes = array_map(fn($i) => array_keys($i), $available_attributes);


        $setting_text = StaticOption::whereIn('option_name', [
            'product_in_stock_text',
            'product_out_of_stock_text',
            'details_tab_text',
            'additional_information_text',
            'reviews_text',
            'your_reviews_text',
            'write_your_feedback_text',
            'post_your_feedback_text',
        ])->get()->mapWithKeys(fn($item) => [$item->option_name => $item->option_value])->toArray();

        return [
            "available_attributes" => $available_attributes,
            "product_inventory_set" => $product_inventory_set,
            "additional_info_store" => $additional_info_store,
            "productColors" => $productColors,
            "productSizes" => $productSizes,
            "product" => $product,
            "setting_text" => $setting_text,
        ];
    }


    public function productQuickViewPage($slug): string
    {
        extract($this->productVariant($slug));

        $sub_category_arr = json_decode($product->sub_category_id, true);

        // related products
        $product_category = $product?->category?->id;
        $product_id = $product->id;
        $related_products = Product::where('status_id', 1)
            ->whereIn('id', function ($query) use ($product_id, $product_category) {
                $query->select('product_categories.product_id')
                    ->from(with(new ProductCategory())->getTable())
                    ->where('product_id', '!=', $product_id)
                    ->where('category_id', '=', $product_category)
                    ->get();
            })
            ->inRandomOrder()
            ->take(5)
            ->get();

        // sidebar data
        $all_category = ProductCategory::all();
        $all_units = ProductUom::all();
        $maximum_available_price = Product::query()->with('category')->max('price');
        $min_price = request()->pr_min ? request()->pr_min : Product::query()->min('price');
        $max_price = request()->pr_max ? request()->pr_max : $maximum_available_price;
        $all_tags = ProductTag::all();

        return themeView(self::BASE_PATH.'product_details.quick-view', compact(
            'product',
            'related_products',
            'available_attributes',
            'product_inventory_set',
            'additional_info_store',
            'all_category',
            'all_units',
            'maximum_available_price',
            'min_price',
            'max_price',
            'all_tags',
            'productColors',
            'productSizes',
            'setting_text',
        ))->render();
    }

    public function category_products($category_type = null, $slug)
    {

        $type = ['category', 'subcategory', 'child-category'];
        abort_if(!in_array($category_type, $type), 404);

        if ($category_type == 'category') {
            $category = Category::where('slug', $slug)->select('id', 'name')->firstOrFail();
            $products_id = ProductCategory::where('category_id', $category->id)->select('product_id')->pluck('product_id');
        } elseif ($category_type == 'subcategory') {
            $category = SubCategory::where('slug', $slug)->select('id', 'name')->firstOrFail();
            $products_id = ProductSubCategory::where('sub_category_id', $category->id)->select('product_id')->pluck('product_id');
        } else {
            $category = ChildCategory::where('slug', $slug)->select('id', 'name')->firstOrFail();
            $products_id = ProductChildCategory::where('child_category_id', $category->id)->select('product_id')->pluck('product_id');
        }

        $products = Product::whereIn('id', $products_id)->paginate(12);

        abort_if(empty($products), 403);

        return themeView(self::BASE_PATH.'single_pages.category', ['category' => $category, 'products' => $products]);
    }

    public function product_by_category_ajax(Request $request)
    {

         $markup = '';

        $products = Product::with('badge')->where('status_id', 1);
        $category_id = Category::where('slug', $request->category)->pluck('id')->toArray();

        $products_id = ProductCategory::whereIn('category_id', $category_id)->pluck('product_id')->toArray();

        $products->whereIn('id', $products_id);

        $products = $products->orderBy('id', 'desc')
            ->select('id', 'name', 'slug', 'price', 'sale_price', 'badge_id', 'image_id')
            ->take($request->limit ?? 6)
            ->get();

        $product_price = '';

        $buy_text = __('Buy Now');
        $markup = '';
        foreach ($products as $product) {
            $badge_markup = '';
            $stock_data = '';
            $img_data = render_image_markup_by_attachment_id($product->image_id, 'grid');
            $data = get_product_dynamic_price($product);
            $campaign_name = $data['campaign_name'];
            $regular_price = $data['regular_price'];
            $sale_price = $data['sale_price'];
            $discount = $data['discount'];

            if(!empty($product->badge)):
                 $badge_markup.= '<span class="sticky stickyStye ratedStock"><i class="fa-solid fa-medal icon"></i>'.$product?->badge?->name.'</span>';
            else:
                 if($product->inventory?->stock_count < 1):
                     $badge_markup.= '<span class="sticky stickyStye outStock">'.__('Out of stock').'</span>';
                 else:

                  endif;
            endif;
            ;

            $product_price ='<div class="productPrice">';
            if($regular_price != null)
                $product_price .= '<strong class="regularPrice">'. amount_with_currency_symbol($sale_price) .'</strong>';

            $product_price .='<span class="offerPrice">'. amount_with_currency_symbol($regular_price) .'</span></div>';// product_prices($product, 'color-two') ;
            $product_name = Str::words($product->name, 4);
            $rating_markup = render_product_star_rating_markup_with_count($product);

            $route = route('tenant.shop.product.details', $product->slug);
            $options = themeView(self::BASE_PATH.'partials.product-options', compact('product'))->render();

            if($product->inventory?->stock_count > 0):
                $stock_data.= '<span class="quintity avilable">'.__('In Stock').' <span class="quintityNumber">('.$product->inventory?->stock_count.')</span> </span>';
            else:
                $stock_data.= '<span class="quintity text-danger">'.__('Stock Out').' <span class="quintityNumber">('.$product->inventory?->stock_count.')</span> </span>';
            endif;

            $markup.= <<<HTML
                <div class="col-xl-4">
                        <div class="singleProduct mb-24">
                            <div class="productImg imgEffect2">
                                <a href="{$route}">
                                   $img_data
                                </a>
                                <!-- sticker -->
                            <div class="sticky-wrap">
                                {$badge_markup}
                            </div>

                                {$options}

                            </div>

                            <div class="productCap">

                                <h5>
                                    <a href="{$route}" class="title">{$product_name} </a>
                                </h5>

                                {$rating_markup}

                                {$stock_data}

                                <div class="d-flex align-items-center flex-wrap justify-content-between">
                                    <div class="productPrice">
                                        {$product_price}
                                    </div>
                                    <div class="btn-wrapper mb-15">
                                        <a href="#!" class="cmn-btn-outline3">{$buy_text}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
HTML;
        }

        return response()->json([
            'markup' => $markup,
            'category' => $request->category,
        ]);
    }

    public function campaign_details($id)
    {
        $campaign = Campaign::findOrFail($id);
        $products = $campaign?->products;
        return themeView(self::BASE_PATH.'campaign-details', compact('campaign', 'products'));
    }


}
