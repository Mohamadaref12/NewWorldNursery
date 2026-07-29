<?php

namespace App\Http\Resources;

use App\Support\ImageUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\InstagramPost */
class InstagramPostResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => ImageUrl::make($this->image),
            'alt' => $this->alt,
            'permalink' => $this->permalink,
            'sort_order' => $this->sort_order,
        ];
    }
}
