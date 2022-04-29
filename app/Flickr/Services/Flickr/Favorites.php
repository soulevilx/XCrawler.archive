<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Services\Flickr\Traits\HasFlickrClient;

class Favorites
{
    use HasFlickrClient;

    public const PER_PAGE = 500;

    /**
     * @param string $user_id
     * @param int|null $min_fave_date
     * @param int|null $max_fave_date
     * @param string|null $extras
     * @param int $per_page
     * @param int $page
     * @return mixed
     * @throws \ReflectionException|FlickrGeneralException
     */
    public function getList(
        string $user_id,
        int    $min_fave_date = null,
        int    $max_fave_date = null,
        string $extras = null,
        int    $per_page = self::PER_PAGE,
        int    $page = 1
    )
    {
        $data = $this->call(func_get_args(), __FUNCTION__);
        $data['photos']['photo'] = collect($data['photos']['photo']);

        return $data['photos'];
    }

    public function getListAll(
        string $user_id,
        int    $min_fave_date = null,
        int    $max_fave_date = null,
        string $extras = null,
        int    $per_page = self::PER_PAGE
    )
    {
        $data = $this->getList($user_id, $min_fave_date, $max_fave_date, $extras, $per_page);
        $list = $data['photo'];

        for ($page = 2; $page <= $data['pages']; $page++) {
            $data = $this->getList($user_id, $min_fave_date, $max_fave_date, $extras, $per_page, $page);
            $list = $list->merge($data['photo']);
        }

        return $list;
    }
}
