<?php

namespace App\Flickr\Services\Flickr;

class Urls extends BaseFlickr
{
    public function lookupUser(string $url)
    {
        return $this->call(func_get_args(), __FUNCTION__)['user'];
    }
}
