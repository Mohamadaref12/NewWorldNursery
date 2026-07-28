<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryItemResource;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GalleryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $type = $request->query('type', GalleryItem::TYPE_MOMENTS);

        $query = GalleryItem::query()->active();

        if ($type === GalleryItem::TYPE_INSTAGRAM) {
            $query->instagram();
        } else {
            $query->moments();
        }

        return GalleryItemResource::collection($query->get());
    }
}
