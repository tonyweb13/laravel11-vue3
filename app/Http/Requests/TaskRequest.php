<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:10',
            'description' => 'required|string|max:255',
            'status' => 'required|in:Pending,Completed',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title is required.',
            'description.required' => 'The description is required.',
            'status.required' => 'The status is required.',
            'title.string' => 'The title must be a string.',
            'description.max' => 'The description may not be greater than 255 characters.',
            'status.in' => 'The status must be one of the following: Pending, Completed.',
        ];
    }
}
