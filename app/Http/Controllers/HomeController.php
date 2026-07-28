<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\GalleryItem;
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
            'momentsItems' => GalleryItem::query()->moments()->active()->get(),
            'galleryItems' => GalleryItem::query()->instagram()->active()->get(),
        ]);
    }
}
