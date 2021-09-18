<?php

namespace App\Jav\Http\Controllers;

use App\Jav\Models\Movie;
use App\Jav\Services\Movie\MovieService;
use App\Jav\Services\WordPressPostService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class MovieController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function postToWordPress(MovieService $service, WordPressPostService $wordPress, Movie $movie)
    {
        $wordPressPost = $service->createWordPressPost($movie);
        if ($wordPressPost) {
            $wordPress->send($wordPressPost);
        }
    }
}
