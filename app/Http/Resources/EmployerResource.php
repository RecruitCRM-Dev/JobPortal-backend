<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployerResource extends JsonResource
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
                'type' => 'employer', 
                'employer_id' => $this->id,
                'attributes' => [
                    'profile_pic' => $this->profile_pic,
                    'name' => $this->name,
                ]
            ],
        ];
    }
}
