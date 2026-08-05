<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryCategoryResource;
use App\Http\Resources\GalleryItemResource;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GalleryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = GalleryItem::query()
            ->withTranslation()
            ->with(['category' => fn ($q) => $q->withTranslation()])
            ->active();

        if ($category = $request->query('category')) {
            $query->whereHas('category', function ($builder) use ($category): void {
                $builder->where(function ($inner) use ($category): void {
                    $inner->whereHas('translations', fn ($q) => $q->where('slug', $category));

                    if (is_numeric($category)) {
                        $inner->orWhere('gallery_categories.id', $category);
                    }
                });
            });
        }

        return GalleryItemResource::collection($query->get());
    }

    public function categories(): AnonymousResourceCollection
    {
        return GalleryCategoryResource::collection(
            GalleryCategory::query()
                ->withTranslation()
                ->active()
                ->with(['items' => fn ($query) => $query->withTranslation()->active()])
                ->get()
        );
    }

    public function showCategory(GalleryCategory $category): GalleryCategoryResource
    {
        abort_unless($category->is_active, 404);

        $category->loadMissing('translations');
        $category->load(['items' => fn ($query) => $query->withTranslation()->active()]);

        return new GalleryCategoryResource($category);
    }
}
