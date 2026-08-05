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
            'features' => Feature::query()->withTranslation()->active()->get(),
            'locations' => Location::query()->withTranslation()->active()->get(),
            'programs' => Program::query()->withTranslation()->active()->get(),
            'momentsItems' => GalleryItem::query()->with(['translations', 'category.translations'])->active()->get(),
            'galleryItems' => InstagramPost::query()->withTranslation()->active()->get(),
        ]);
    }
}
