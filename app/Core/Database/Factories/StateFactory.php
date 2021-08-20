<?php

namespace App\Core\Database\Factories;

use App\Core\Models\State;

class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition()
    {
        return [
            'reference_code' => $this->faker->uuid,
            'entity' => $this->faker->word,
            'state' => strtoupper($this->faker->word()),
        ];
    }
}
