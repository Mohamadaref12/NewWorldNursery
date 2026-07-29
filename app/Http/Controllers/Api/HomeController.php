<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureResource;
use App\Http\Resources\GalleryCategoryResource;
use App\Http\Resources\GalleryItemResource;
use App\Http\Resources\InstagramPostResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\SiteSettingResource;
use App\Models\Feature;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\InstagramPost;
use App\Models\Location;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $galleryItems = GalleryItem::query()->with('category')->active()->get();
        $instagramPosts = InstagramPost::query()->active()->get();

        return response()->json([
            'data' => [
                'settings' => new SiteSettingResource(SiteSetting::current()),
                'features' => FeatureResource::collection(Feature::query()->active()->get()),
                'locations' => LocationResource::collection(Location::query()->active()->get()),
                'programs' => ProgramResource::collection(Program::query()->active()->get()),
                'gallery_categories' => GalleryCategoryResource::collection(
                    GalleryCategory::query()->active()->get()
                ),
                // Categorized site gallery (Moments of Joy, etc.)
                'gallery_items' => GalleryItemResource::collection($galleryItems),
                'moments' => GalleryItemResource::collection($galleryItems),
                // Instagram feed (BC: historically exposed as "gallery")
                'instagram' => InstagramPostResource::collection($instagramPosts),
                'gallery' => InstagramPostResource::collection($instagramPosts),
            ],
        ]);
    }
}
