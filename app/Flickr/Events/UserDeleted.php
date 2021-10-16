<?php

namespace App\Flickr\Events;

class UserDeleted
{
    public function __construct(public string $nsid)
    {
    }
}
