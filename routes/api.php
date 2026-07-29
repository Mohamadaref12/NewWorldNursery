<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FeatureController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\InstagramController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\SiteSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/home', HomeController::class);
Route::get('/settings', [SiteSettingController::class, 'show']);
Route::get('/features', [FeatureController::class, 'index']);
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/locations/{location}', [LocationController::class, 'show']);
Route::get('/programs', [ProgramController::class, 'index']);
Route::get('/programs/{program}', [ProgramController::class, 'show']);
Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/gallery/categories', [GalleryController::class, 'categories']);
Route::get('/gallery/categories/{category}', [GalleryController::class, 'showCategory']);
Route::get('/instagram', [InstagramController::class, 'index']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/latest', [BlogController::class, 'latest']);
Route::get('/blogs/{blog}', [BlogController::class, 'show']);
Route::post('/contact', [ContactController::class, 'store']);
