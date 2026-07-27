<?php

namespace App\Http\Resources;

use App\Support\ImageUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'image' => ImageUrl::make($this->image),
            'sort_order' => $this->sort_order,
        ];
    }
}
