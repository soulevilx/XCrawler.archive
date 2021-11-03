<?php

namespace App\Flickr\Events;

use App\Flickr\Services\Flickr\People;
use App\Flickr\Services\Flickr\PhotoSets;

class FlickrRequestFailed
{
    protected array $pathMaps = [
        'flickr.people' => People::class,
        'flickr.contacts' => People::class,
        'flickr.photosets' => PhotoSets::class,
    ];

    public function __construct(public string $path, public array $params, public array $response)
    {
    }
}
