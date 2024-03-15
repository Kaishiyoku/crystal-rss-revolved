<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FeedDiscovererController;
use App\Http\Controllers\FeedUrlDiscovererController;
use App\Http\Controllers\MarkAllUnreadFeedItemsAsReadController;
use App\Http\Controllers\ToggleFeedItemController;
use Illuminate\Http\Request;
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

Route::get('/', function (Request $request) {
    if ($request->user())
    {
        return view('app_react');
    }

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'contactEmail' => config('app.contact_email'),
        'githubUrl' => config('app.github_url'),
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('verified')->group(function () {
        Route::resource('categories', CategoryController::class)->except('show');

        Route::post('discover-feed', FeedDiscovererController::class)->name('discover-feed');
        Route::post('discover-feed-urls', FeedUrlDiscovererController::class)->name('discover-feed-urls');

        Route::put('/feeds/mark-all-as-read', MarkAllUnreadFeedItemsAsReadController::class)->name('mark-all-as-read');
        Route::resource('feeds', FeedController::class)->except('show');
        Route::put('/feeds/{feedItem}/toggle', ToggleFeedItemController::class)->name('toggle-feed-item');
    });
});

require __DIR__.'/auth.php';

Route::get('{all?}', function() {
    return view('app_react');
})->where(['all' => '.*']);
