<?php

namespace App\Jav\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Jav\Models\Onejav;

class OnejavFactory extends Factory
{
    protected $model = Onejav::class;

    public function definition()
    {
        return [
            'url' => $this->faker->unique->url,
            'cover' => $this->faker->unique->url,
            'dvd_id' => $this->faker->unique->uuid,
            'size' => $this->faker->randomFloat(2, 10, 20),
            'date' => $this->faker->date,
            'genres' => [
                $this->faker->unique->word,
                $this->faker->unique->word,
                $this->faker->unique->word
            ],
            'performers' => [
                $this->faker->unique->name,
                $this->faker->unique->name,
                $this->faker->unique->name
            ],
            'description' => $this->faker->text,
            'torrent' => $this->faker->unique->url,
        ];
    }
}

