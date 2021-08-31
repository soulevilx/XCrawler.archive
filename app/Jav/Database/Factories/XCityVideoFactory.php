<?php

namespace App\Jav\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Core\Models\State;
use App\Jav\Models\XCityVideo;

class XCityVideoFactory extends Factory
{
    protected $model = XCityVideo::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'url' => $this->faker->url,
            'cover' => $this->faker->url,
            'sales_date' => $this->faker->date,
            'release_date' => $this->faker->date,
            'item_number' => $this->faker->uuid,
            'dvd_id' => $this->faker->uuid,
            'description' => $this->faker->text,
            'running_time' => $this->faker->numerify,
            'director' => $this->faker->name,
            'studio' => $this->faker->name,
            'marker' => $this->faker->name,
            'label' => $this->faker->name,
            'channel' => $this->faker->name,
            'series' => $this->faker->text,
            'gallery' => [
                $this->faker->unique->url,
                $this->faker->unique->url,
                $this->faker->unique->url,
            ],
            //            'sample' => [
            //                $this->faker->unique->url,
            //                $this->faker->unique->url,
            //                $this->faker->unique->url,
            //            ],
            'genres' => [
                $this->faker->unique->word,
                $this->faker->unique->word,
                $this->faker->unique->word,
            ],
            'actresses' => [
                $this->faker->unique->name,
                $this->faker->unique->name,
                $this->faker->unique->name,
            ],
            'favorite' => $this->faker->numerify,
            'state_code' => State::STATE_INIT,
        ];
    }
}
