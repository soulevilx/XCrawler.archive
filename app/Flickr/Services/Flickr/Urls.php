<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Services\Flickr\Traits\HasFlickrClient;

class Urls
{
    use HasFlickrClient;

    public function lookupUser(string $url)
    {
        return $this->call(func_get_args(), __FUNCTION__)['user'];
    }
}
