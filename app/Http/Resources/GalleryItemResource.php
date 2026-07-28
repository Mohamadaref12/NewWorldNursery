<?php

namespace App\Http\Resources;

use App\Support\ImageUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\GalleryItem */
class GalleryItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'image' => ImageUrl::make($this->image),
            'alt' => $this->alt,
            'sort_order' => $this->sort_order,
        ];
    }
}
