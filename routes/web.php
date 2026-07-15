<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Path-based locales for SEO: / (en), /ar, /ckb. English stays at the root.
Route::get('/{locale?}', HomeController::class)
    ->where('locale', 'ar|ckb')->name('home');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')->name('contact.store');

Route::get('/admin/login', [Admin\LoginController::class, 'create'])->name('login');
Route::post('/admin/login', [Admin\LoginController::class, 'store'])
    ->middleware('throttle:5,1')->name('login.store');
Route::post('/admin/logout', [Admin\LoginController::class, 'destroy'])->name('logout');

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::resource('services', Admin\ServiceController::class)->except('show');
    Route::resource('projects', Admin\ProjectController::class)->except('show');
    Route::resource('testimonials', Admin\TestimonialController::class)->except('show');

    Route::get('strings', [Admin\UiStringController::class, 'index'])->name('strings.index');
    Route::put('strings', [Admin\UiStringController::class, 'update'])->name('strings.update');
    Route::post('strings/reset', [Admin\UiStringController::class, 'reset'])->name('strings.reset');

    Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');

    Route::get('inbox', [Admin\InboxController::class, 'index'])->name('inbox.index');
    Route::get('inbox/{submission}', [Admin\InboxController::class, 'show'])->name('inbox.show');
    Route::patch('inbox/{submission}/toggle', [Admin\InboxController::class, 'toggle'])->name('inbox.toggle');
    Route::delete('inbox/{submission}', [Admin\InboxController::class, 'destroy'])->name('inbox.destroy');

    Route::get('media', [Admin\MediaController::class, 'index'])->name('media.index');
    Route::post('media', [Admin\MediaController::class, 'store'])->name('media.store');
    Route::delete('media/{media}', [Admin\MediaController::class, 'destroy'])->name('media.destroy');

    // Inline edit API (public-page edit-in-place for logged-in admins)
    Route::post('api/inline-text', [Admin\InlineEditController::class, 'text'])->name('api.inline-text');
    Route::post('api/inline-image', [Admin\InlineEditController::class, 'image'])->name('api.inline-image');
    Route::post('api/sections', [Admin\InlineEditController::class, 'sections'])->name('api.sections');
    Route::post('api/reorder', [Admin\InlineEditController::class, 'reorder'])->name('api.reorder');
    Route::post('api/items', [Admin\InlineEditController::class, 'storeItem'])->name('api.items.store');
    Route::delete('api/items', [Admin\InlineEditController::class, 'destroyItem'])->name('api.items.destroy');
});
