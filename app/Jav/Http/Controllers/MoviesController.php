<?php

namespace App\Jav\Http\Controllers;

use App\Core\Http\Controllers\BaseController;
use App\Jav\Http\Requests\PostWordPressRequest;
use App\Jav\Http\Requests\ShowMoviesRequest;
use App\Jav\Models\Genre;
use App\Jav\Models\Movie;
use App\Jav\Models\Performer;
use App\Jav\Services\Movie\MovieService;
use App\Jav\Services\R18Service;
use App\Jav\Services\WordPressService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

class MoviesController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function index(ShowMoviesRequest $request)
    {
        $options = [
            'searchIn' => [
                'dvd_id',
                'content_id',
                'description',
            ],
        ];
        if ($request->filled('genres')) {
            $genres = is_array($request->input('genres')) ? $request->input('genres') : [$request->input('genres')];
            $genres = Genre::whereIn('name', $genres)->pluck('id')->toArray();
            $movieIds = DB::table('movie_genres')->whereIn('genre_id', $genres)->pluck('movie_id')->toArray();
            $request->merge(
                [
                    'whereIn' => array_merge(
                        $request->input('whereIn') ?? [],
                        ['id' => $movieIds],
                    ),
                ]
            );
        }

        if ($request->filled('performers')) {
            $performers = is_array($request->input('performers')) ? $request->input('performers') : [$request->input('performers')];
            $performers = Performer::whereIn('name', $performers)->pluck('id')->toArray();
            $movieIds = DB::table('movie_performers')->whereIn('performer_id', $performers)->pluck('movie_id')->toArray();
            $request->merge(
                [
                    'whereIn' => array_merge(
                        $request->input('whereIn') ?? [],
                        ['id' => $movieIds],
                    ),
                ]
            );
        }

        return response()->view(
            'pages.jav.index',
            [
                'movies' => $this->_index(Movie::class, $request, $options),
            ]
        );
    }

    public function toWordPress(PostWordPressRequest $request, WordPressService $wordPress, Movie $movie)
    {
        if (!$wordPressPost = $wordPress->createMoviePost($movie, $request->input('confirm', false))) {
            return response()->view(
                'pages.jav.movie',
                [
                    'movie' => $movie,
                    'confirm' => [
                        'message' => 'Confirm repost',
                    ],
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

    public function resync(Movie $movie, R18Service $service)
    {
        if ($movie->r18) {
            $service->refetch($movie->r18);
            $movie->refresh();
        }

        return redirect()->route('movie.show', ['movie' => $movie]);
    }

    public function requestDownload(Movie $movie, MovieService $service)
    {
        $service->requestDownload($movie);

        return redirect()->route('movie.show', ['movie' => $movie]);
    }
}
