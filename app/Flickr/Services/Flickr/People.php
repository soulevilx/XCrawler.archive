<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Events\Errors\UserDeleted;
use App\Flickr\Events\Errors\UserNotFound;
use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Repositories\ContactRepository;
use Illuminate\Support\Collection;

class People extends BaseFlickr
{
    public const ERROR_CODE_USER_NOT_FOUND = 1;
    public const ERROR_CODE_USER_DELETED = 5;

    public const EVENT_MAPS = [
        self::ERROR_CODE_USER_NOT_FOUND => UserNotFound::class,
        self::ERROR_CODE_USER_DELETED => UserDeleted::class,
    ];

    public const ERROR_MESSAGES_MAP = [
        self::ERROR_CODE_USER_NOT_FOUND => 'The user id passed did not match a Flickr user.',
        self::ERROR_CODE_USER_DELETED => 'The user id passed matched a deleted Flickr user.'
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
    ): Collection
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

    public function update(FlickrContact $model, array $attributes): bool
    {
        if ($return = $model->update($attributes)) {
            /**
             * @TODO Trigger updated event
             * - Pending update for at least 1 week later
             */
            $model->processes()->create([
                'step' => FlickrProcess::STEP_PEOPLE_INFO,
            ]);
        }

        return $return;
    }

    public function addPhotos(FlickrContact $model, Collection $photos)
    {
        foreach ($photos as $photo) {
            $model->photos()->firstOrCreate([
                'id' => $photo['id'],
                'owner' => $photo['owner'],
            ], $photo);
        }

        $model->processes()->create([
            'step' => FlickrProcess::STEP_PEOPLE_PHOTOS,
        ]);
    }
}
