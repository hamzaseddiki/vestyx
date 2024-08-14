<?php

namespace Modules\Job\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
{

    public function rules() : array
    {
        return [
            'category_id' => 'required|string',
            'title' => 'nullable|string',
            'slug' => 'nullable|string',
            'description' => 'required|string',
            'experience' => 'required|string',
            'designation' => 'required|string',
            'employee_type' => 'nullable|string',
            'working_days' => 'nullable|string',
            'working_type' => 'nullable|string',
            'job_location' => 'nullable|string',
            'salary_offer' => 'required|string',
            'image' => 'required|string',
            'deadline' => 'required|string',
            'status' => 'required|string',
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
