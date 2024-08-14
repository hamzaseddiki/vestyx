<?php

namespace Modules\Knowledgebase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KnowledgebaseRequest extends FormRequest
{

    public function rules() : array
    {
        return [
            'category_id' => 'required|string',
            'title' => 'required|string',
            'slug' => 'nullable|string',
            'description' => 'required|string',
            'status' => 'nullable|string',
            'image' => 'required',
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
