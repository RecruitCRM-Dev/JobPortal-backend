<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use App\Models\JobApplication;

class JobApplicationRequest extends FormRequest
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
            'job_id' => 'required',
            'user_id' => [
                'required',
                Rule::unique('job_applications')->where(function ($query) {
                    return $query->where('job_id', $this->job_id)
                                 ->where('user_id', $this->user_id);
                })
            ],
            'status' => 'required| in:Just_Applied,ResumeViewed,Underconsideration,Rejected,Selected'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data' => $validator->errors()
        ], 422));
    }
}
