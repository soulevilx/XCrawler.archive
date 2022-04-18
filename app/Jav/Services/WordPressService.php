<?php

namespace App\Jav\Services;

use App\Core\Models\State;
use App\Core\Models\WordPressPost;
use App\Core\Models\WordPressPost as WordPressPostModel;
use App\Jav\Mail\WordPressPost as WordPressPostEmail;
use App\Jav\Models\Movie;
use Illuminate\Support\Facades\Mail;

class WordPressService
{
    public function createMoviePost(Movie $movie, bool $force = false): ?WordPressPost
    {
        if (!$force && $movie->wordpress()->where('state_code', State::STATE_COMPLETED)->exists()) {
            return null;
        }

        return $movie->wordpress()->create([
            'title' => $movie->dvd_id ?? $movie->content_id,
            'state_code' => State::STATE_INIT,
        ]);
    }

    public function send(?WordPressPost $post = null)
    {
        if ($post === null && !$post = WordPressPostModel::byState(State::STATE_INIT)->first()) {
            return;
        }

        $post->setState(State::STATE_PROCESSING);
        Mail::send(new WordPressPostEmail($post->model));
        $post->setState(State::STATE_COMPLETED);
    }
}
