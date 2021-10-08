<?php

use App\Flickr\Http\Controllers\FlickrController;

Route::namespace('App\Flickr\Http\Controllers')
    ->prefix('flickr')
    ->middleware(['web'])
    ->group(function () {

        Route::get('/', [FlickrController::class, 'index'])->name('flickr.index');
        Route::post('/album/download', [FlickrController::class, 'downloadAlbum'])->name('flickr.album.download');
    });
