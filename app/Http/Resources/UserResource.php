<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "firstName" => $this->firstName,
            "middleName" => $this->middleName,
            "lastName" => $this->lastName,
            "mobile" => $this->mobile,
            "email" => $this->email,
            "registeredAt" => $this->registeredAt,
            "lastLogin" => $this->lastLogin,
            "intro" => $this->intro,
            "profile" => $this->profile,
            "posts" => PostResource::collection($this->whenLoaded('posts')),
        ];
    }
}