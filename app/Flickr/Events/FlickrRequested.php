<?php

namespace App\Flickr\Events;

class FlickrRequested
{
    public function __construct(
        public string $method,
        public string $path,
        public array $params,
        public array $jsonResponse
    ) {
    }

    public function isSucceed(): bool
    {
        return $this->jsonResponse['stat'] && $this->jsonResponse['stat'] !== 'fail';
    }
}
