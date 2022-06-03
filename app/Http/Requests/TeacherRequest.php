<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|regex:/^[\w][a-zA-Z\s]+$/',
            'father_name'=>'nullable|string|regex:/^[\w][a-zA-Z\s]+$/',
            'nrc_number'=>'nullable|string',
            'phone_no'=>'nullable|string|min:1|max:11',
            'email'=>'email|required|regex:/^([^"!\'\*\\\\]*)$/',
            'gender'=>'required|integer|between:1,2',
            'date_of_birth'=>'nullable|date',
            'avatar'=>'nullable|mimes:jpg,jpeg,png,bmp,tiff,webp|max:4096',
            'address'=>'nullable|string',
            'career_path'=>'nullable|integer|between:1,2',
            'skills'=>'nullable|array',
            'emp_id'=>'nullable|integer',
            'skills.*'=>'nullable|integer|between:1,6',
        ];
    }
}
