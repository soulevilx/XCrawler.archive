<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Services\FlickrService;
use Illuminate\Support\Collection;

class Contacts extends BaseFlickr
{
    public function getAll()
    {
        $contacts  = $this->getList(null, 1);
        $pages = $contacts['pages'];

        /**
         * @var Collection $list
         */
        $list = $contacts['contact'];

        for ($page = 2; $page <= $pages; $page++) {
            $contacts = $this->getList(null, $page);
            $list = $list->merge($contacts['contact']);
        }

        return $list;
    }

    /**
     * @link https://www.flickr.com/services/api/flickr.contacts.getList.html
     * @param string|null $filter
     * @param int|null $page
     * @param int|null $perPage
     * @param string|null $sort
     * @return mixed
     * @throws \Exception
     */
    public function getList(?string $filter = null, ?int $page = null, ?int $perPage = 1000, ?string $sort = null)
    {
        $params = [
            'filter' => $filter,
            'page' => $page,
            'per_page' => $perPage,
            'sort' => $sort
        ];

        $response = $this->service->request($this->buildPath(__FUNCTION__), $params);
        $response['contacts']['contact'] = collect($response['contacts']['contact']);

        return $response['contacts'];
    }
}
