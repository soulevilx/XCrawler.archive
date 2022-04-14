<?php

namespace App\Jav\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Jav\Models\Genre;

class GenreFactory  extends Factory
{
    protected $model = Genre::class;

    public function definition()
    {
        return [
            'name' => $this->faker->text
        ];
    }
}
