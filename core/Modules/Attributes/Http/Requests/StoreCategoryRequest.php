<?php

namespace Modules\Attributes\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|string|max:191|unique:categories',
            'slug' => 'nullable|string|max:191',
            'description' => 'nullable',
            'status_id' => 'required|string|max:191',
            'image_id' => 'nullable|string|max:191',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
