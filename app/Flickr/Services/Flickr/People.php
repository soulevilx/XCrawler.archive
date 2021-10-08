<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Exceptions\FlickrRequestFailed;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\FlickrService;

class People extends BaseFlickr
{
    public function getInfo(string $user_id): ?array
    {
        try {
            return $this->call(func_get_args(), __FUNCTION__)['person'];
        } catch (FlickrRequestFailed $exception) {
            switch ($exception->getCode()) {
                case FlickrService::FLICKR_ERROR_USER_DELETED:
                    FlickrContact::where('nsid', $user_id)->delete();
                    return null;
            }
        }
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
     * @throws \ReflectionException
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
        int    $per_page = 500,
        int    $page = 1
    ): array
    {
        try {
            $response = $this->call(func_get_args(), __FUNCTION__);
            $response['photos']['photo'] = collect($response['photos']['photo']);

            return $response['photos'];
        } catch (FlickrRequestFailed $exception) {
            switch ($exception->getCode()) {
                case FlickrService::FLICKR_ERROR_USER_DELETED:
                    FlickrContact::where('nsid', $user_id)->delete();
                   break;
            }
        }

        return [];
    }

    public function getPhotosAll(
        string $user_id,
        int    $safe_search = 3,
        int    $min_upload_date = null,
        int    $max_upload_date = null,
        int    $min_taken_date = null,
        int    $content_type = null,
        int    $privacy_filter = null,
        string $extras = null,
        int    $per_page = 500,
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
