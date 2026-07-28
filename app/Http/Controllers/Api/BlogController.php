<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BlogController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return BlogResource::collection(
            Blog::query()->active()->get()
        );
    }

    public function latest(Request $request): AnonymousResourceCollection
    {
        $limit = min(max((int) $request->query('limit', 5), 1), 20);

        return BlogResource::collection(
            Blog::query()->latestPublished($limit)->get()
        );
    }

    public function show(Blog $blog): BlogResource
    {
        abort_unless(
            $blog->is_active
            && $blog->published_at
            && $blog->published_at->lte(now()),
            404
        );

        return new BlogResource($blog);
    }
}
