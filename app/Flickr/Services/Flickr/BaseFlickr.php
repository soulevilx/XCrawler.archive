<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Services\FlickrService;

class BaseFlickr
{
    protected string $namespace;

    public function __construct(protected FlickrService $service)
    {
        $reflect = new \ReflectionClass($this);
        $this->namespace = strtolower($reflect->getShortName());
    }

    protected function buildPath(string $method): string
    {
        return 'flickr.' . $this->namespace . '.' . $method;
    }
}
