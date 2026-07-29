<?php

use App\Http\Controllers\Admin\InstagramOAuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware(['auth'])->prefix('admin/instagram')->group(function () {
    Route::get('/redirect', [InstagramOAuthController::class, 'redirect'])->name('admin.instagram.redirect');
    Route::get('/callback', [InstagramOAuthController::class, 'callback'])->name('admin.instagram.callback');
});
