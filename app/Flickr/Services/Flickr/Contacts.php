<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Events\FlickrContactCreated;
use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Repositories\ContactRepository;
use Illuminate\Support\Facades\Event;

class Contacts extends BaseFlickr
{
    public const PER_PAGE = 1000;
    public const ERROR_CODE_INVALID_SORT_PARAMETER = 1;

    public function getListAll(?string $filter = null, ?int $page = null, ?int $per_page = self::PER_PAGE, ?string $sort = null)
    {
        $data = $this->getList($filter, $page, $per_page, $sort);
        $list = $data['contact'];

        for ($page = 2; $page <= $data['pages']; $page++) {
            $data = $this->getList($filter, $page, $per_page, $sort);
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
     * @throws \ReflectionException|FlickrGeneralException
     */
    public function getList(?string $filter = null, ?int $page = null, ?int $per_page = self::PER_PAGE, ?string $sort = null)
    {
        $data = $this->call(func_get_args(), __FUNCTION__);
        $data['contacts']['contact'] = collect($data['contacts']['contact']);

        return $data['contacts'];
    }

    public function create(array $attributes): FlickrContact
    {
        $repository = app(ContactRepository::class);
        $model = $repository->firstOrCreate([
            'nsid' => $attributes['nsid'],
        ], $attributes);

        if ($model->wasRecentlyCreated) {
            Event::dispatch(new FlickrContactCreated($model));
        }

        return $model;
    }
}
