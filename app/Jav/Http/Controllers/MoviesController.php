<?php

namespace App\Jav\Http\Controllers;

use App\Core\Http\Controllers\BaseController;
use App\Jav\Http\Requests\ShowMoviesRequest;
use App\Jav\Models\Genre;
use App\Jav\Models\Movie;
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

        return response()->view(
            'pages.jav.index',
            [
                'movies' => $this->_index(Movie::class, $request, $options),
            ]
        );
    }
}
