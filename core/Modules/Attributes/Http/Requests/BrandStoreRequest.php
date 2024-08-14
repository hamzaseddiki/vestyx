<?php

namespace Modules\Attributes\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class BrandStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(["name" => "string", "status" => "integer", "slug" => "string", "title" => "string", "description" => "string","url" => "string" ,"image_id" => "string", "banner_id" => "string"])]
    public function rules(): array
    {
        return [
            "name" => ["required","string" , Rule::unique('product_brands')->ignore($this->id ?? 0)],
            "slug" => ["nullable","string" , Rule::unique('product_brands')->ignore($this->id ?? 0)],
            "description" => ["nullable","string"],
            "url" => ["nullable","string"],
            "image_id" => ["required","string"],
            "banner_id" => ["nullable","string"],
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
