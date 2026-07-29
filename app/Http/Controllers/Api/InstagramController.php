<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InstagramPostResource;
use App\Models\InstagramPost;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InstagramController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return InstagramPostResource::collection(
            InstagramPost::query()->active()->get()
        );
    }
}
