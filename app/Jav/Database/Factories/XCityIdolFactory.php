<?php

namespace App\Jav\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Core\Models\State;
use App\Jav\Models\XCityIdol;

class XCityIdolFactory extends Factory
{
    protected $model = XCityIdol::class;

    public function definition()
    {
        return [
            'url' => $this->faker->url,
            'name' => $this->faker->name,
            'cover' => $this->faker->url,
            'favorite' => $this->faker->numerify,
            'birthday' => $this->faker->date,
            'blood_type' => $this->faker->randomElement(['A', 'B', 'AB', 'O']),
            'city' => $this->faker->city,
            'height' => $this->faker->numerify,
            'breast' => $this->faker->numerify,
            'waist' => $this->faker->numerify,
            'hips' => $this->faker->numerify,
            'skill' => $this->faker->text,
            'other' => $this->faker->text,
            'state_code' => State::STATE_INIT,
        ];
    }
}
