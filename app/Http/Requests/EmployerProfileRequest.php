<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployerProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'about'=>'string|max:255',
            'address' => '', 
            'experience' => 'integer|min:0', 
            'profile_pic' => 'string|max:255', 
        ];
    }

    // public function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(response()->json([
    //         'data' => $validator->errors()
    //     ]));
    // }
}
