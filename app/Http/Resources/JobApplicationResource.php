<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'job_id' => $this->job_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserProfileResource($this->whenLoaded('user')), // Include user information directly
            'job' => new JobDetailResource($this->whenLoaded('job'))
        ];
    }
}
