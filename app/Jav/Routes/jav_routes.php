<?php

use App\Jav\Http\Controllers\OnejavController;
use App\Jav\Http\Controllers\MovieController;

Route::namespace('App\Jav\Http\Controllers')
    ->prefix('jav')
    ->middleware(['web'])
    ->group(function () {
        // Onejav
        Route::get('/onejav/{onejav}/download', [OnejavController::class, 'download'])->name('onejav.download');

        // Movies
        Route::get('/movies/{movie}/to-wordpress', [MovieController::class, 'postToWordPress'])->name('movie.to-wordpress');
    });
