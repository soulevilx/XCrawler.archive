<?php

namespace App\Jav\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Jav\Models\Movie;

class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition()
    {
        return [
            'name' => $this->faker->title,
            'cover' => $this->faker->url,
            'sales_date' => $this->faker->date,
            'release_date' => $this->faker->date,
            'content_id' => $this->faker->uuid,
            'dvd_id' => $this->faker->uuid,
            'description' => $this->faker->realText,
            'time' => $this->faker->numerify,
            'director' => $this->faker->name,
            'studio' => $this->faker->title,
            'label' => $this->faker->title,
            'channels' => [
                $this->faker->title,
            ],
            'series' => [
                $this->faker->title,
            ],
            'gallery' => [
                $this->faker->unique->url,
                $this->faker->unique->url,
                $this->faker->unique->url,
            ],
            'images' => [
                $this->faker->unique->url,
                $this->faker->unique->url,
                $this->faker->unique->url,
            ],
            'sample' => [
                $this->faker->unique->url,
                $this->faker->unique->url,
                $this->faker->unique->url,
            ],
            'is_downloadable' => $this->faker->boolean,
        ];
    }
}
