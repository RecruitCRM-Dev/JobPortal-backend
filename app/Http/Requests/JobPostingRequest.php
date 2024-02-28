<?php

namespace App\Http\Requests;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;

class JobPostingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' =>  'required|string',
            'responsibilities' => 'required|string',
            'category' => 'required|in:' . implode(',', Job::$category),
            'salary' => 'required|numeric|min:5000',
            'location' => 'required|string',
            'type' => 'required|in:' . implode(',', Job::$jobType),
        ];
    }
}
