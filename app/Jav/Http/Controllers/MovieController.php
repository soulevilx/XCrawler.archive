<?php

namespace App\Jav\Http\Controllers;

use App\Core\Http\Controllers\BaseResourceController;
use App\Jav\Http\Resources\WordPressPostResource;
use App\Jav\Models\Movie;
use App\Jav\Services\Movie\MovieService;
use App\Jav\Services\WordPressPostService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class MovieController extends BaseResourceController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function postToWordPress(MovieService $service, WordPressPostService $wordPress, Movie $movie)
    {
        $wordPressPost = $service->createWordPressPost($movie);
        if (!$wordPressPost) {
            return $this->respondNoContent();
        }

        $wordPress->send($wordPressPost);
        return $this->respondOk(new WordPressPostResource($wordPressPost->refresh()));
    }
}
