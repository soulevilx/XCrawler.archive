<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\Traits\HasFlickrMiddleware;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrDownload;
use App\Flickr\Models\FlickrDownloadItem;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Services\FlickrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class FlickrRequestDownloadAlbum implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use HasFlickrMiddleware;

    public function __construct(public int $albumId, public string $nsid)
    {
    }

    public function handle(FlickrService $service)
    {
        $albumInfo = $service->photosets()->getInfo($this->albumId, $this->nsid);

        FlickrContact::firstOrCreate([
            'nsid' => $albumInfo['owner'],
        ], [
            'state_code' => State::STATE_INIT,
        ]);

        /**
         * @var FlickrAlbum $album
         */
        $album = FlickrAlbum::firstOrCreate([
            'id' => $albumInfo['id'],
            'owner' => $albumInfo['owner'],
            'state_code' => State::STATE_INIT,
        ], $albumInfo);

        $flickrDownload = FlickrDownload::create([
            'name' => $album->title,
            'path' => $album->owner . '/' . Str::slug($album->title),
            'total' => $album->photos,
            'model_id' => $album->id,
            'model_type' => FlickrAlbum::class,
            'state_code' => State::STATE_INIT,
        ]);

        $photos = $service->photosets()->getAllPhotos($album->id, $album->owner);
        $photos->each(function ($photo) use ($album, $flickrDownload) {
            $photo = FlickrPhoto::updateOrCreate([
                'id' => $photo['id'],
                'owner' => $album->owner,
            ], [
                'secret' => $photo['secret'],
                'server' => $photo['server'],
                'farm' => $photo['farm'],
                'title' => $photo['title'],
            ]);
            $album->photos()->syncWithoutDetaching([$photo->id]);

            // Create download item
            FlickrDownloadItem::create([
                'download_id' => $flickrDownload->id,
                'photo_id' => $photo->id,
                'state_code' => State::STATE_INIT
            ]);
        });
    }
}
