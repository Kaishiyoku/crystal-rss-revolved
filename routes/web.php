<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FeedDiscovererController;
use App\Http\Controllers\FeedUrlDiscovererController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ToggleFeedItemController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categories', CategoryController::class)->except('show');

    Route::post('discover-feed', FeedDiscovererController::class)->name('discover-feed');
    Route::post('discover-feed-urls', FeedUrlDiscovererController::class)->name('discover-feed-urls');

    Route::resource('feeds', FeedController::class)->except('show');
    Route::put('/feeds/{feedItem}/toggle', ToggleFeedItemController::class)->name('toggle-feed-item');
});

require __DIR__.'/auth.php';
