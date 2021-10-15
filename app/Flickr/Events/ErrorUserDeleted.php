<?php

namespace App\Flickr\Events;

class ErrorUserDeleted
{
    public function __construct(public string $nsid)
    {
    }
}
