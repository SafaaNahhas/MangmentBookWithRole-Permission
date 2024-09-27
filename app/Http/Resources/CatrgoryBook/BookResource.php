<?php

namespace App\Http\Resources\CatrgoryBook;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'published_at' => $this->published_at,
            'is_active' => $this->is_active,
            'category' => $this->category ? $this->category->name : null,
        ];
    }
}
