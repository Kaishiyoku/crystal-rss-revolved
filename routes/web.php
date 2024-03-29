<?php

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
    if ($request->user()) {
        return view('app');
    }

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'contactEmail' => config('app.contact_email'),
        'githubUrl' => config('app.github_url'),
    ]);
});

Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {

    });
});

require __DIR__.'/auth.php';

Route::get('{all?}', function () {
    return view('app');
})->where(['all' => '.*']);
