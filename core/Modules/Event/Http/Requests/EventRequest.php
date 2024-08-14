<?php

namespace Modules\Event\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{

    public function rules() : array
    {
        return [
            'title' => 'required|string',
            'slug' => 'nullable|string',
            'description' => 'required|string',
            'cost' => 'required|string',
            'status' => 'required|string',
            'category_id' => 'required|string',
            'image' => 'nullable|string',
            'date' => 'nullable|string',
            'time' => 'nullable|string',
            'total_ticket' => 'nullable|string',
            'organizer' => 'required|string',
            'organizer_email' => 'required|string',
            'organizer_phone' => 'required|string',
            'venue_location' => 'required|string',
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
