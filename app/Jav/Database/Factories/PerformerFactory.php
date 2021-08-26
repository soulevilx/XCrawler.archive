<?php

namespace App\Jav\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Jav\Models\Performer;

class PerformerFactory extends Factory
{
    protected $model = Performer::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'alias' => $this->faker->name,
            'birthday' => $this->faker->date,
            'blood_type' => $this->faker->randomElement(['A', 'B', 'AB', 'O']),
            'city' => $this->faker->city,
            'height' => $this->faker->numerify,
            'breast' => $this->faker->numerify,
            'waist' => $this->faker->numerify,
            'hips' => $this->faker->numerify,
            'cover' => $this->faker->url,
            'favorite' => $this->faker->numerify,
        ];
    }
}
