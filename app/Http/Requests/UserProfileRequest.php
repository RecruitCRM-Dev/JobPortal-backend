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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'gender' => 'required|in:Male,Female,Other', 
            'phone' => 'required|string|max:20', 
            'address' => 'required|string|max:255', 
            'resume' => 'required|string|max:255', 
            'experience' => 'required|integer|min:0', 
            'profile_pic' => 'required|string|max:255', 
            'education' => 'required|string|max:255', 
            'skills' => 'required|string|max:255'
        ];
    }
    
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data' => $validator->errors()
        ]));
    }
}
