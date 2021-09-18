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
            'nsid' => '94529704@N02',
            'state_code' => State::STATE_INIT,
        ];
    }
}
