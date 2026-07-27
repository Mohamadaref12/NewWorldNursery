<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin \App\Models\Location */
class LocationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'country' => $this->country,
            'badge_color' => $this->badge_color,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'working_hours' => $this->working_hours,
            'map_url' => $this->map_url,
            'visit_url' => $this->visit_url,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'sort_order' => $this->sort_order,
        ];
    }
}
