<?php

namespace App\Jav\Services;

use App\Core\Models\State;
use App\Core\Models\WordPressPost;
use App\Core\Models\WordPressPost as WordPressPostModel;
use App\Jav\Mail\WordPressPost as WordPressPostEmail;
use Illuminate\Support\Facades\Mail;

class WordPressPostService
{
    public function send(?WordPressPost $post = null)
    {
        if ($post === null) {
            if (!$post = WordPressPostModel::byState(State::STATE_INIT)->first()) {
                return;
            }
        }

        $post->setState(State::STATE_PROCESSING);
        Mail::send(new WordPressPostEmail($post->model));
        $post->setState(State::STATE_COMPLETED);
    }
}
