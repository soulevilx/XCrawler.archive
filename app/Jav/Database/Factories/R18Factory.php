<?php

namespace App\Jav\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Jav\Models\State;
use App\Jav\Models\R18;

class R18Factory extends Factory
{
    protected $model = R18::class;

    public function definition()
    {
        return [
            'url' => $this->faker->unique->url,
            'cover' => $this->faker->unique->url,
            'title' => $this->faker->unique->title,
            'release_date' => $this->faker->date,
            'runtime' => $this->faker->numerify,
            'director' => $this->faker->name,
            'studio' => $this->faker->unique->name,
            'maker' => $this->faker->unique->name,
            'label' => $this->faker->unique->name,
            'channels' => [
                $this->faker->unique->name,
                $this->faker->unique->name,
                $this->faker->unique->name,
            ],
            'content_id' => $this->faker->uuid,
            'dvd_id' => $this->faker->uuid,
            'series' => $this->faker->title,
            'languages' => $this->faker->languageCode,
            'sample' => [
                $this->faker->unique->url,
                $this->faker->unique->url,
                $this->faker->unique->url,
            ],
            'images' => [
                $this->faker->unique->url,
                $this->faker->unique->url,
                $this->faker->unique->url,
            ],
            'gallery' => [
                $this->faker->unique->url,
                $this->faker->unique->url,
                $this->faker->unique->url,
            ],
            'genres' => [
                $this->faker->unique->word,
                $this->faker->unique->word,
                $this->faker->unique->word,
            ],
            'performers' => [
                $this->faker->unique->name,
                $this->faker->unique->name,
                $this->faker->unique->name,
            ],
            'state_code' => State::STATE_INIT,
        ];
    }
}
