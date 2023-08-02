<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'SKU' => $this->SKU,
            'size' => $this->size,
            'photo_url' => $this->photo_url,
            'content' => new ProductContentResource($this->content),
            'tags' => ProductTagResource::collection($this->tags),
            'stocks' => ProductStockResource::collection($this->stocks)
        ];
    }
}
