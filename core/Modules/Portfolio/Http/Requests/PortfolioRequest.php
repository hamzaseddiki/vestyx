<?php

namespace Modules\Portfolio\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioRequest extends FormRequest
{

    public function rules() : array
    {
        return [
            'title' => 'required|string',
            'slug' => 'nullable|string',
            'description' => 'required|string',
            'status' => 'required|string',
            'category_id' => 'required|string',
            'image' => 'required|string',
            'image_gallery' => 'nullable|string',
            'url' => 'nullable|string',
        ];
    }


    public function messages() : array
    {
        return [
            'category_id.required' => 'Category field is required'
        ];
    }


    public function authorize()
    {
        return true;
    }
}
