<?php

namespace App\Jav\Http\Controllers;

use App\Core\Models\Download;
use App\Jav\Http\Requests\DownloadRequest;
use App\Jav\Models\Movie;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class DownloadController extends CrudController
{
    public function download(DownloadRequest $request, Movie $movie)
    {
        $movie->download()->create([
            'user_id' => backpack_user()->id,
        ]);
    }
}
