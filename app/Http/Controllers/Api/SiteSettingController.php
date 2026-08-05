<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class SiteSettingController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json([
            'locale' => app()->getLocale(),
            'data' => new SiteSettingResource(SiteSetting::current()),
        ]);
    }
}
