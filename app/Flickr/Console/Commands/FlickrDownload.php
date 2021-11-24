<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Jobs\FlickrRequestDownloadAlbum;

class FlickrDownload extends AbstractBaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:download {task} {--url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all contacts from authorized user';

    public function flickrAlbum()
    {
        $album = $this->service->downloadAlbum($this->option('url'));

        $this->output->table([
            'url',
            'album_id',
            'owner',
            'status',
        ], [
            [
                $this->option('url'),
                $album->getAlbumId(),
                $album->getUserNsid(),
                'queued',
            ],
        ]);

        return true;
    }

    public function flickrAlbums()
    {
        $nsid = $this->service->urls()->lookupUser($this->option('url'));
        if (!$nsid) {
            return true;
        }

        $nsid = $nsid['id'];
        $albums = $this->service->photosets()->getListAll($nsid);

        $data = [];
        foreach ($albums as $album) {
            $data[] = [
                $this->option('url'),
                $album['id'],
                $album['owner'],
                'queued',
            ];

            FlickrRequestDownloadAlbum::dispatch(
                $album['id'],
                $album['owner'],
            )->onQueue('api');
        }

        $this->output->table([
            'url',
            'album_id',
            'owner',
            'status',
        ], $data);
    }
}
