<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'sort_order' => $this->sort_order,
        ];
    }
}
