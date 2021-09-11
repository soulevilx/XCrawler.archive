<?php

namespace App\Flickr\Events;

use App\Flickr\Models\FlickrContact;

class FlickrContactCreated
{
    public function __construct(public FlickrContact $contact)
    {
    }
}
