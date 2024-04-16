<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $author = User::find($this->authorId);
        $authorName = "$author->firstName $author->lastName";
        
        return [
        "id" => $this->id,
        "author"=> $authorName,
        "title" => $this->title,
        "metaTitle"=> $this->metaTitle,
        "slug"=> $this->slug,
        "sumary"=> $this->sumary,
        "published"=> $this->published,
        "createdAt"=> $this->createdAt,
        "updatedAt"=> $this->updatedAt,
        "publishedAt"=> $this->publishedAt,
        "content"=> $this->content
        ];
    }
}