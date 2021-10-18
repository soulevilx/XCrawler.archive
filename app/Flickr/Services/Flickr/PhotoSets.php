<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Events\Errors\PhotosetNotFound;

class PhotoSets extends BaseFlickr
{
    public const ERROR_CODE_PHOTOSET_NOT_FOUND = 1;
    public const ERROR_CODE_PHOTOSET_USER_FOUND = 2;

    public const EVENT_MAPS = [
        self::ERROR_CODE_PHOTOSET_NOT_FOUND => PhotosetNotFound::class,
    ];

    public function getList(
        string  $user_id,
        int     $page = 1,
        int     $per_page = 500,
        ?string $primary_photo_extras = null,
        ?string $photo_ids = null,
        ?string $sort_groups = null
    )
    {
        $response = $this->call(func_get_args(), __FUNCTION__);

        $response['photosets']['photoset'] = collect($response['photosets']['photoset']);

        return $response['photosets'];
    }

    public function getListAll(
        string  $user_id,
        int     $page = 1,
        int     $per_page = 500,
        ?string $primary_photo_extras = null,
        ?string $photo_ids = null,
        ?string $sort_groups = null
    )
    {
        $photosets = $this->getList(
            $user_id,
            $page,
            $per_page,
            $primary_photo_extras,
            $photo_ids,
            $sort_groups
        );

        $pages = $photosets['pages'];
        $list = $photosets['photoset'];

        for ($page = 2; $page <= $pages; $page++) {
            $photosets = $this->getList(
                $user_id,
                $page,
                $per_page,
                $primary_photo_extras,
                $photo_ids,
                $sort_groups
            );
            $list = $list->merge($photosets['photoset']);
        }

        return $list;
    }

    public function getPhotos(
        string  $photoset_id,
        string  $user_id,
        ?string $extra = null,
        ?int    $page = 1,
        ?int    $per_page = 500,
        ?int    $privacy_filter = null,
        ?string $media = null
    )
    {
        $response = $this->call(func_get_args(), __FUNCTION__);
        $response['photoset']['photo'] = collect($response['photoset']['photo']);

        return $response['photoset'];
    }

    /**
     * @param string $photoset_id
     * @param string $user_id
     * @param string|null $extra
     * @param int|null $page
     * @param int|null $per_page
     * @param int|null $privacy_filter
     * @param string|null $media
     * @return mixed
     */
    public function getAllPhotos(
        string  $photoset_id,
        string  $user_id,
        ?string $extra = null,
        ?int    $page = 1,
        ?int    $per_page = 500,
        ?int    $privacy_filter = null,
        ?string $media = null
    )
    {
        $photos = $this->getPhotos(
            $photoset_id,
            $user_id,
            $extra,
            $page,
            $per_page,
            $privacy_filter,
            $media,
        );

        $pages = $photos['pages'];
        $list = $photos['photo'];

        for ($page = 2; $page <= $pages; $page++) {
            $photos = $this->getPhotos(
                $photoset_id,
                $user_id,
                $extra,
                $page,
                $per_page,
                $privacy_filter,
                $media,
            );
            $list = $list->merge($photos['photo']);
        }

        return $list;
    }

    public function getInfo(int $photoset_id, string $user_id): array
    {
        return $this->call(func_get_args(), __FUNCTION__)['photoset'];
    }
}
