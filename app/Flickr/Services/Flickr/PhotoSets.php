<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Events\AlbumCreated;
use App\Flickr\Events\Errors\PhotosetNotFound;
use App\Flickr\Events\PhotoAddedToAlbum;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Repositories\AlbumRepository;
use App\Flickr\Repositories\PhotoRepository;
use App\Flickr\Services\Flickr\Traits\HasFlickrClient;
use App\Flickr\Services\FlickrService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

class PhotoSets
{
    use HasFlickrClient;

    public const ERROR_CODE_PHOTOSET_NOT_FOUND = 1;
    public const ERROR_CODE_PHOTOSET_USER_FOUND = 2;

    public const EVENT_MAPS = [
        self::ERROR_CODE_PHOTOSET_NOT_FOUND => PhotosetNotFound::class,
    ];

    public function __construct(private FlickrService $service)
    {
    }

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
    ): Collection
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
    ): array
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

    public function create(array $attributes): FlickrAlbum
    {
        $repository = app(AlbumRepository::class);
        $model = $repository->firstOrCreate([
            'id' => $attributes['id'],
            'owner' => $attributes['owner'],
        ], $attributes);

        if ($model->wasRecentlyCreated) {
            Event::dispatch(new AlbumCreated($model));
        }

        return $model;
    }

    public function addPhoto(FlickrAlbum $album, array $photo)
    {
        unset($photo['isprimary']);
        unset($photo['ispublic']);
        unset($photo['isfriend']);
        unset($photo['isfamily']);

        $model = FlickrPhoto::withTrashed()->firstOrCreate([
            'id' => $photo['id'],
            'owner' => $album->owner,
        ], $photo);

        $album->photos()->syncWithoutDetaching([$model->id]);

        if ($model->wasRecentlyCreated) {
            Event::dispatch(new PhotoAddedToAlbum($album, $model));
        }
    }

    public function addPhotos(FlickrAlbum $album)
    {
        $this->getAllPhotos($album->id, $album->owner)->each(function ($photo) use ($album) {
            $this->service->photosets()->addPhoto($album, $photo);
        });
    }
}
