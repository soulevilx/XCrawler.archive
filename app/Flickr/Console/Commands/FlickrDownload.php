<?php

namespace App\Flickr\Console\Commands;

class FlickrDownload extends AbstractFlickrCommand
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
        $albums = $this->service->downloadAlbums($this->option('url'));

        $data = [];
        foreach ($albums as $album) {
            $data[] = [
                $this->option('url'),
                $album['id'],
                $album['owner'],
                'queued',
            ];
        }

        $this->output->table([
            'url',
            'album_id',
            'owner',
            'status',
        ], $data);
    }
}
