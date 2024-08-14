<?php

namespace Modules\Attributes\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required','string','max:191', Rule::unique('categories')->ignore($this->id)],
            'slug' => ['required','string','max:191', Rule::unique('categories')->ignore($this->id)],
            'description' => 'nullable',
            'status_id' => 'required|string|max:191',
            'image_id' => 'nullable|max:191',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
