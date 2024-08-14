<?php

namespace Modules\Appointment\Http\Requests;

use App\Helpers\FlashMsg;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{

    public function rules() : array
    {
        $data =  [
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'required',
            'image' => 'nullable',
            'is_popular' => 'nullable',
        ];

        if(!empty($this->sub_appointment_status)){
            $data['sub_appointment_ids'] = 'required';
        }

        if(!empty($this->tax_status) && $this->tax_type == 'exclusive'){
            $data['tax_amount'] = 'required';
        }

        return $data;
    }

    public function messages() : array
    {
        return [
            'sub_appointment_ids.required' => 'Sub Appointment field is required'
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {

    }
}
