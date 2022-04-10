<?php

namespace App\Jav\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Jav\Models\Onejav;
use Illuminate\Support\Str;

class OnejavFactory extends Factory
{
    protected $model = Onejav::class;

    public function definition()
    {
        $dvdId = $this->faker->unique->uuid;
        return [
            'url' => '/torrent/' . Str::slug($dvdId),
            'cover' => $this->faker->unique->url,
            'dvd_id' => strtolower($dvdId),
            'size' => $this->faker->randomFloat(2, 10, 20),
            'date' => $this->faker->date,
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
            'description' => $this->faker->text,
            'torrent' => '/torrent/' . Str::slug($dvdId) . '/download/' . $this->faker->uuid
        ];
    }
}
