<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Events\Errors\UserDeleted;
use App\Flickr\Exceptions\FlickrGeneralException;

class People extends BaseFlickr
{
    public const ERROR_CODE_USER_NOT_FOUND = 1;
    public const ERROR_CODE_USER_DELETED = 5;

    public const EVENT_MAPS = [
        self::ERROR_CODE_USER_DELETED => UserDeleted::class,
    ];

    public const PER_PAGE = 500;

    public function getInfo(string $user_id): array
    {
        return $this->call(func_get_args(), __FUNCTION__)['person'];
    }

    /**
     * @param string $user_id
     * @param int $safe_search
     * @param int|null $min_upload_date
     * @param int|null $max_upload_date
     * @param int|null $min_taken_date
     * @param int|null $content_type
     * @param int|null $privacy_filter
     * @param string|null $extras
     * @param int $per_page
     * @param int $page
     * @return array
     * @throws \ReflectionException|FlickrGeneralException
     */
    public function getPhotos(
        string $user_id,
        int    $safe_search = 3,
        int    $min_upload_date = null,
        int    $max_upload_date = null,
        int    $min_taken_date = null,
        int    $content_type = null,
        int    $privacy_filter = null,
        string $extras = null,
        int    $per_page = self::PER_PAGE,
        int    $page = 1
    ): array
    {
        $response = $this->call(func_get_args(), __FUNCTION__);
        $response['photos']['photo'] = collect($response['photos']['photo']);

        return $response['photos'];
    }

    public function getPhotosAll(
        string $user_id,
        int    $safe_search = 3,
        int $min_upload_date = null,
        int    $max_upload_date = null,
        int    $min_taken_date = null,
        int    $content_type = null,
        int    $privacy_filter = null,
        string $extras = null,
        int    $per_page = self::PER_PAGE,
        int    $page = 1
    )
    {
        $photos = $this->getPhotos(
            $user_id,
            $safe_search,
            $min_upload_date,
            $max_upload_date,
            $min_taken_date,
            $content_type,
            $privacy_filter,
            $extras,
            $per_page,
            $page
        );

        $pages = $photos['pages'];
        $list = $photos['photo'];

        for ($page = 2; $page <= $pages; $page++) {
            $photos = $this->getPhotos(
                $user_id,
                $safe_search,
                $min_upload_date,
                $max_upload_date,
                $min_taken_date,
                $content_type,
                $privacy_filter,
                $extras,
                $per_page,
                $page
            );
            $list = $list->merge($photos['photo']);
        }

        return $list;
    }
}
