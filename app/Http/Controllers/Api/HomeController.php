<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureResource;
use App\Http\Resources\GalleryItemResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\SiteSettingResource;
use App\Models\Feature;
use App\Models\GalleryItem;
use App\Models\Location;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => [
                'settings' => new SiteSettingResource(SiteSetting::current()),
                'features' => FeatureResource::collection(Feature::query()->active()->get()),
                'locations' => LocationResource::collection(Location::query()->active()->get()),
                'programs' => ProgramResource::collection(Program::query()->active()->get()),
                'gallery' => GalleryItemResource::collection(
                    GalleryItem::query()->instagram()->active()->get()
                ),
                'moments' => GalleryItemResource::collection(
                    GalleryItem::query()->moments()->active()->get()
                ),
            ],
        ]);
    }
}
