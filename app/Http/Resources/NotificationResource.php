<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "data" =>[
                'id' => $this->id,
                'user_id' => $this->notifiable_id,
                'job_id' => $this->data['job_id'],
                'status' => $this->data['status'],
                'job_title' => $this->data['job_title'],
                'message' => $this->data['message'],
                'created_at' => $this->created_at->diffForHumans(),
                'read_at' => $this->read_at,
            ]
            
        ];
    }
}
