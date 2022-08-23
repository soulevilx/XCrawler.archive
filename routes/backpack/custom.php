<?php

use App\Core\Http\Controllers\DashboardController;
use App\Jav\Http\Controllers\MovieController;
use App\Jav\Http\Controllers\OnejavController;
use App\Jav\Http\Controllers\R18Controller;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/onejav', [OnejavController::class, 'index'])->name('onejav.index');
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/r18', [R18Controller::class, 'index'])->name('r18.index');
}); // this should be the absolute last line of this file
