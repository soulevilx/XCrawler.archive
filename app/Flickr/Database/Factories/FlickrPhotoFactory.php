<?php

namespace App\Flickr\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrPhoto;

class FlickrPhotoFactory extends Factory
{
    protected $model = FlickrPhoto::class;

    public function definition()
    {
        return [
            'id' => $this->faker->numberBetween(1),
            'owner' => FlickrContact::factory()->create(['nsid' => $this->faker->uuid])->nsid
        ];
    }
}
