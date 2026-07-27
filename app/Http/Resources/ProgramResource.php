<?php

namespace App\Http\Resources;

use App\Support\ImageUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Program */
class ProgramResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'age_range' => $this->age_range,
            'description' => $this->description,
            'color' => $this->color,
            'icon' => $this->icon,
            'icon_color' => $this->icon_color,
            'image' => ImageUrl::make($this->image),
            'sort_order' => $this->sort_order,
        ];
    }
}
