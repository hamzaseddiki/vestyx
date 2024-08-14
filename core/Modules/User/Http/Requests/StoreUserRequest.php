<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|max:191|unique:users',
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191|unique:users',
            'address' => 'nullable|string|max:191',
            'zipcode' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'state' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:191',
            'password' => 'required|string|min:8|confirmed'
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
