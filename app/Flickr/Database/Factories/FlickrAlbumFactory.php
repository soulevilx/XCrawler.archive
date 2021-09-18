<?php

namespace App\Flickr\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Core\Models\State;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;

class FlickrAlbumFactory extends Factory
{
    protected $model = FlickrAlbum::class;

    public function definition()
    {
        $contact = FlickrContact::findByNsid('94529704@N02') ?? FlickrContact::factory()->create();
        return [
            'id' => $this->faker->numerify,
            'owner' => $contact->nsid,
            'state_code' => State::STATE_INIT,
        ];
    }
}
