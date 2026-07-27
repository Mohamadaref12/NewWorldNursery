<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LocationController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return LocationResource::collection(
            Location::query()->active()->get()
        );
    }

    public function show(Location $location): LocationResource
    {
        abort_unless($location->is_active, 404);

        return new LocationResource($location);
    }
}
