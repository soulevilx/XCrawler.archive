<?php

use App\Jav\Http\Controllers\MoviesController;
use App\Jav\Http\Controllers\OnejavController;
use App\Jav\Http\Controllers\MovieController;

Route::namespace('App\Jav\Http\Controllers')
    ->prefix('jav')
    ->middleware(['web'])
    ->group(function () {
        // Onejav
        Route::post('/onejav/{onejav}/download', [OnejavController::class, 'download'])->name('onejav.download');

        // Movies
        Route::get('/movies', [MoviesController::class, 'index'])->name('movies.index');
        Route::post('/movies/{movie}/to-wordpress', [MovieController::class, 'toWordPress'])->name('movie.to-wordpress');
        Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movie.show');
        Route::post('/movies/{movie}/resync', [MovieController::class, 'resync'])->name('movie.resync');
    });
