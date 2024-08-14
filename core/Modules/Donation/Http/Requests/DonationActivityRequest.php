<?php

namespace Modules\Donation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationActivityRequest extends FormRequest
{

    public function rules() : array
    {
        return [
            'title' => 'required|string',
            'slug' => 'nullable|string',
            'description' => 'required|string',
            'status' => 'required|string',
            'category_id' => 'required|string',
            'image' => 'nullable|string',
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
