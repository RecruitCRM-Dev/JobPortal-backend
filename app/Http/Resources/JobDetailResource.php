<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\EmployerProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'type' => 'jobs',
                'job_id' => $this->id,
                'attributes' => [
                    'title' => $this->title,
                    'description' => $this->description,
                    'category' => $this->category,
                    'experience' => $this->experience,
                    'salary' => $this->salary,
                    'status' => $this->status,
                    'location' => $this->location,
                    'posted_by' => new EmployerProfileResource($this->employer),
                    'posted_at' => $this->created_at->diffForHumans(),
                    'created_at' => $this->created_at
                ]
            ],
        ];
    }
}
