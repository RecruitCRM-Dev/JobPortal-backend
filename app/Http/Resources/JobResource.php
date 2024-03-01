<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'job' => [
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'category' => $this->category,
                'experience' => $this->experience,
                'location' => $this->location,
                'salary' => $this->salary,
                'status' => $this->status,
                'employer' => $this->employer,
            ]
        ];
    }
}
