<?php

namespace App\Flickr\Events\Errors;

class UserDeleted
{
    public function __construct(public string $path, public array $params)
    {
    }
}
