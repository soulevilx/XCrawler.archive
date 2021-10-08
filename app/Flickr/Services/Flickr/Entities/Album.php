<?php

namespace App\Flickr\Services\Flickr\Entities;

use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Services\FlickrService;

class Album
{
    private string $albumUrl;
    private int $albumId;
    private array $user;

    public function __construct(protected FlickrService $service)
    {
    }

    public function loadFromUrl(string $albumUrl)
    {
        $this->albumUrl = $albumUrl;
        $url = explode('/', $albumUrl);
        $this->albumId = end($url);

        FlickrAlbum::where('id', $this->albumId)->update([
            'url' => $albumUrl,
        ]);
    }

    public function getUserNsid(): string
    {
        if (empty($this->user)) {
            $this->user = $this->service->urls()->lookupUser($this->albumUrl);
        }

        return $this->user['id'];
    }

    public function getAlbumId(): int
    {
        return $this->albumId;
    }
}
