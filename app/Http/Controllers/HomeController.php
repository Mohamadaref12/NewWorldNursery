<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\GalleryItem;
use App\Models\InstagramPost;
use App\Models\Location;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'settings' => SiteSetting::current(),
            'features' => Feature::query()->active()->get(),
            'locations' => Location::query()->active()->get(),
            'programs' => Program::query()->active()->get(),
            'momentsItems' => GalleryItem::query()->with('category')->active()->get(),
            'galleryItems' => InstagramPost::query()->active()->get(),
        ]);
    }
}
