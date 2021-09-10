<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Services\FlickrService;
use Illuminate\Support\Collection;

class Contacts extends BaseFlickr
{
    /**
     * @link https://www.flickr.com/services/api/flickr.contacts.getList.html
     * @param string|null $filter
     * @param int|null $page
     * @param int|null $per_page
     * @param string|null $sort
     * @return array
     * @throws \ReflectionException
     */
    public function getList(?string $filter = null, ?int $page = null, ?int $per_page = 1000, ?string $sort = null)
    {
        return $this->call(func_get_args(), __FUNCTION__);
    }
}
