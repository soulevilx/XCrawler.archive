<?php

use App\Core\Http\Controllers\ConfigurationsController;

Route::namespace('App\Core\Http\Controllers')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/configurations', [ConfigurationsController::class, 'index'])->name('configurations.index');
        Route::put('/configurations', [ConfigurationsController::class, 'update'])->name('configurations.update');
    });
