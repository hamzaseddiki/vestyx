<?php

namespace Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubAppointmentRequest extends FormRequest
{

    public function rules() : array
    {
        return [
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'required',
            'image' => 'nullable',
            'is_popular' => 'nullable',
        ];
    }



    public function authorize()
    {
        return true;
    }
}
