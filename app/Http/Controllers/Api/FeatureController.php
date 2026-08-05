<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureResource;
use App\Models\Feature;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FeatureController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return FeatureResource::collection(
            Feature::query()->withTranslation()->active()->get()
        );
    }
}
