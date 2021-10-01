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

    public function toWordPress(MovieService $service, WordPressPostService $wordPress, Movie $movie)
    {
        $wordPressPost = $service->createWordPressPost($movie);
        if (!$wordPressPost) {
            return response()->view(
                'jav.movie',
                [
                    'movie' => $movie,
                    'messages' => [
                        [
                            'type' => 'danger',
                            'message' => 'Can not create WordPress Post',
                        ],
                    ],
                ]
            );
        }

        $wordPress->send($wordPressPost);
        return response()->view(
            'jav.movie',
            [
                'movie' => $movie->refresh(),
                'messages' => [
                    [
                        'type' => 'primary',
                        'message' => 'WordPress Post sent',
                    ],
                ],
            ]
        );
    }

    public function show(Movie $movie)
    {
        return response()->view(
            'jav.movie',
            [
                'movie' => $movie->refresh(),
            ]
        );
    }
}
