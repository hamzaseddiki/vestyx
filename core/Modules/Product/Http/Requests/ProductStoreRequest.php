<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            "name" => "required",
            "slug" => "required|unique:products,id," . $this->id ?? 0,
            "summery" => "required",
            "description" => "required",
            "lang" => "nullable",
            "brand" => "nullable",
            "cost" => "required",
            "price" => "nullable",
            "sale_price" => "required",
            "sku" => ["required", ($this->id ?? null) ? Rule::unique("product_inventories")->ignore($this->id,"product_id") :  Rule::unique("product_inventories")],
            "quantity" => "nullable",
            "unit_id" => "required",
            "uom" => "required",
            "image_id" => "required",
            "product_gallery" => "nullable",
            "tags" => "required",
            "badge_id" => "nullable",
            "item_size" => "nullable",
            "item_color" => "nullable",
            "item_image" => "nullable",
            "item_additional_price" => "nullable",
            "item_extra_price" => "nullable",
            "item_stock_count" => "nullable",
            "item_extra_cost" => "nullable",
            "item_attribute_id" => "nullable",
            "item_attribute_name" => "nullable",
            "item_attribute_value" => "nullable",
            "category_id" => "required",
            "sub_category" => "required",
            "child_category" => "required",
            "delivery_option" => "nullable",
            "general_title" => "nullable",
            "general_description" => "nullable",
            "general_image" => "nullable",
            "facebook_title" => "nullable",
            "facebook_description" => "nullable",
            "facebook_image" => "nullable",
            "twitter_title" => "nullable",
            "twitter_description" => "nullable",
            "twitter_image" => "nullable",
            "min_purchase" => "nullable",
            "max_purchase" => "nullable",
            "is_refundable" => "nullable",
            "is_inventory_warn_able" => "nullable",
            "policy_description" => "nullable"
         ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            "is_inventory_warn_able" => $this->is_inventory_warning
        ]);
    }

    public function messages(): array
    {
        return [
            "cost.required" => "Cost filed is required for your accounting...",
            "price.required" => "Regular price is required.",
            "sku.required" => "SKU Stock Kipping Unit is required",
            "uni.required" => "Please Select a unit type",
            "uom.required" => "UOM Unit of measurement field is required."
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
