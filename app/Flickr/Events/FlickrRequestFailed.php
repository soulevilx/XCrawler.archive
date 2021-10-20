<?php

namespace App\Flickr\Events;

use App\Flickr\Services\Flickr\People;
use App\Flickr\Services\Flickr\PhotoSets;
use Illuminate\Support\Facades\Event;

class FlickrRequestFailed
{
    protected array $pathMaps = [
        'flickr.people' => People::class,
        'flickr.contacts' => People::class,
        'flickr.photosets' => PhotoSets::class,
    ];

    public function __construct(public string $path, public array $params, public array $response)
    {
        if (!empty($this->response)) {
            foreach (array_keys($this->pathMaps) as $key) {
                if (!str_contains($this->path, $key)) {
                    continue;
                }

                $targetClass = $this->pathMaps[$key];

                if (isset($this->response['code']) && $targetClass::EVENT_MAPS[$this->response['code']]) {
                    $eventClass = $targetClass::EVENT_MAPS[$this->response['code']];
                    Event::dispatch(new $eventClass($this->path, $this->params));
                }
            }
        }
    }
}
