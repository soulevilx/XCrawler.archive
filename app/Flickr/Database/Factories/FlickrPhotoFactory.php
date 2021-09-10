<?php

namespace App\Flickr\Database\Factories;

use App\Core\Database\Factories\Factory;
use App\Flickr\Models\FlickrPhoto;

class FlickrPhotoFactory extends Factory
{
    protected $model = FlickrPhoto::class;

    public function definition()
    {
        return [
            'owner' => '94529704@N02',
        ];
    }
}
