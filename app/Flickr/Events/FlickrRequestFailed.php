<?php

namespace App\Flickr\Events;

class FlickrRequestFailed
{
    public function __construct(public string $path, public array $params, public array $response)
    {
    }
}
