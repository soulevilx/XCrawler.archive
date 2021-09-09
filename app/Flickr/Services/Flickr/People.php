<?php

namespace App\Flickr\Services\Flickr;

class People extends BaseFlickr
{
    public function getInfo(string $nsid)
    {
        $response = $this->service->request(
            $this->buildPath(__FUNCTION__),
            ['user_id' => $nsid]
        );

        return $response['person'];
    }
}
