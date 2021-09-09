<?php

namespace App\Flickr\Services\Flickr;

class People extends BaseFlickr
{
    public function getInfo(string $user_id)
    {
        $response = $this->service->request(
            $this->buildPath(__FUNCTION__),
            ['user_id' => $user_id]
        );

        return $response['person'];
    }

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
        $response = $this->call(func_get_args(), __FUNCTION__);
        $response['photos']['photo'] = collect($response['photos']['photo']);

        return $response['photos'];
    }

    public function getAllPhotos(
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
