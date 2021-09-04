<?php

use App\Jav\Http\Controllers\OnejavController;

Route::namespace('App\Jav\Http\Controllers')
    ->prefix('jav')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/onejav/{onejav}/download', [OnejavController::class, 'download'])->name('onejav.download');
    });
