<?php

namespace App\Core\Database\Factories;

use App\Core\Models\State;
use App\Core\Models\WordPressPost;

class WordPressPostFactory extends Factory
{
    protected $model = WordPressPost::class;

    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'state_code' => State::STATE_INIT,
        ];
    }
}
