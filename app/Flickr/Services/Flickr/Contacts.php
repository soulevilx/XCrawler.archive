<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Services\FlickrService;
use Illuminate\Support\Collection;

class Contacts extends BaseFlickr
{
    public function getListAll(?string $filter = null, ?int $page = null, ?int $per_page = 1000, ?string $sort = null)
    {
        $data = $this->getList($filter, $page, $per_page, $sort);
        $list = $data['contact'];

        for ($page = 2; $page <= $data['pages']; $page++) {
            $data =$this->getList($filter, $page,$per_page, $sort);
            $list = $list->merge($data['contact']);
        }

        return $list;
    }
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
        $data = $this->call(func_get_args(), __FUNCTION__);
        $data['contacts']['contact'] = collect($data['contacts']['contact']);

        return $data['contacts'];
    }
}
