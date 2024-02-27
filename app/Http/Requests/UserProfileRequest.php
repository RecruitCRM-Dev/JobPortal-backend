<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'name' => 'string|max:255',
            // 'email' => 'string|email|max:255',
            // 'about'=>'string|max:255',
            // 'gender' => 'in:Male,Female,Other',
            // 'phone' => 'string|max:20',
            // 'role'=>'in:Software Developer, Graphic Designer, Sales, HR, Business, Project Manager, Marketing',
            // 'address' => 'string|max:255',
            // 'resume' => 'string|max:255',
            // 'experience' => 'integer|min:0',
            // 'profile_pic' => 'string|max:255',
            // 'education' => 'string|max:255',
            // 'skills.*' => 'in:HTML5,Javascript,Vue,Laravel,ReactJS, Python, Java, Django',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data' => $validator->errors()
        ],400));
    }
}
