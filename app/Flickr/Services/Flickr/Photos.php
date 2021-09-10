<?php

namespace App\Flickr\Services\Flickr;

class Photos extends BaseFlickr
{
    public function getSizes(int $photo_id): array
    {
        $response = $this->call(func_get_args(), __FUNCTION__);
        $response['sizes']['size'] = collect($response['sizes']['size']);

        return $response['sizes'];
    }
}
