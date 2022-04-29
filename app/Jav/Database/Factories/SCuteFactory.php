<?php

namespace App\Jav\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Jav\Models\SCute;

class SCuteFactory extends Factory
{
    protected $model = SCute::class;

    public function definition()
    {
        return [
            'url' => $this->faker->url,
            'cover' => $this->faker->unique->url,
        ];
    }
}
