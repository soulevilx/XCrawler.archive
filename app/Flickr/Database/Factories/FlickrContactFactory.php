<?php

namespace App\Flickr\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;

class FlickrContactFactory extends Factory
{
    protected $model = FlickrContact::class;

    public function definition()
    {
        return [
            'nsid' => $this->faker->uuid,
            'state_code' => State::STATE_INIT,
        ];
    }
}
