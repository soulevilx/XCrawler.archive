<?php

namespace App\Flickr\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrContactProcess;

class FlickrContactProcessFactory extends Factory
{
    protected $model = FlickrContactProcess::class;

    public function definition()
    {
        return [
            'model_id' => FlickrContact::factory()->create()->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrContactProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_INIT,
        ];
    }
}

