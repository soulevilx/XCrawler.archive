<?php

namespace App\Flickr\Events\Errors;

class UserNotFound
{
    public function __construct(public string $path, public array $params)
    {
    }
}
