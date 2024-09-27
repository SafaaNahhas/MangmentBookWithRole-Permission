<?php

namespace App\Http\Resources\CatrgoryBook;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CatrgoryBook\BookResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray(request: $request);
        return [
            'id' => $this->id, 
            'name' => $this->name,
            'books' => BookResource::collection($this->whenLoaded('books')),
        ];
    }
}
