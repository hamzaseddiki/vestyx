<?php

namespace Modules\Attributes\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class UpdateSubCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(["id" => "string", 'name' => "string", 'slug' => "string", 'description' => "string", 'status_id' => "string", 'image_id' => "string", 'category_id' => "string"])]
    public function rules()
    {
        return [
            "id" => "required",
            'name' => ['required','string','max:191', Rule::unique('sub_categories')->ignore($this->id)],
            'slug' => 'nullable|string|max:191',
            'description' => 'nullable',
            'status_id' => 'required|string|max:191',
            'image_id' => 'nullable|string|max:191',
            'category_id' => 'required|max:191'
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
