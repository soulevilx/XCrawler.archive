<?php

namespace App\Flickr\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;

class FlickrProcessFactory extends Factory
{
    protected $model = FlickrProcess::class;

    public function definition()
    {
        $contact = FlickrContact::findByNsid('94529704@N02') ?? FlickrContact::factory()->create();
        return [
            'model_id' => $contact->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_INIT,
        ];
    }
}

