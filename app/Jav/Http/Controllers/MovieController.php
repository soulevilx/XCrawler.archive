<?php

namespace App\Jav\Http\Controllers;

use App\Core\Http\Controllers\BaseResourceController;
use App\Core\Models\WordPressPost;
use App\Jav\Http\Requests\PostWordPressRequest;
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

    public function toWordPress(PostWordPressRequest $request, MovieService $service, WordPressPostService $wordPress, Movie $movie)
    {
        if (!$wordPressPost = $service->createWordPressPost($movie, $request->input('confirm', false))) {
                return response()->view(
                    'pages.jav.movie',
                    [
                        'movie' => $movie,
                        'messages' => [
                            [
                                'type' => 'danger',
                                'message' => 'Can not create WordPress Post' . $request->input('confirm'),
                            ],
                        ],
                        'confirm' => [
                            'message' => 'Confirm repost',
                        ]
                    ]
                );
        }

        $wordPress->send($wordPressPost);
        return redirect()->route('movie.show', ['movie' => $movie]);
    }

    public function show(Movie $movie)
    {
        return response()->view(
            'pages.jav.movie',
            [
                'movie' => $movie,
            ]
        );
    }

    public function resync(Movie $movie)
    {
        $movie?->r18?->refetch();

        return redirect()->route('movie.show', ['movie' => $movie]);
    }
}
