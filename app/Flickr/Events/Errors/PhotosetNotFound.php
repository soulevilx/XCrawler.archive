<?php

namespace App\Flickr\Events\Errors;

class PhotosetNotFound
{
    public function __construct(public string $path, public array $params)
    {
    }
}
