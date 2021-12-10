<?php

namespace App\Flickr\Http\Controllers;

use App\Core\Http\Controllers\BaseResourceController;
use App\Flickr\Http\Requests\DownloadAlbumRequest;
use App\Flickr\Services\FlickrService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class FlickrController extends BaseResourceController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function index()
    {
        return response()->view(
            'pages.flickr.index',
        );
    }

    public function downloadAlbum(DownloadAlbumRequest $request, FlickrService $service)
    {
        $album = $service->downloadAlbum($request->input('url'));

        session()->flash(
            'messages',
            [
                [
                    'message' => sprintf(
                        'Downloading Albumid <strong>%s</strong> from NSID %s',
                        $album->getAlbumId(),
                        $album->getUserNsid()
                    ),
                    'type' => 'info'
                ],
            ]
        );

        return response()->view('pages.flickr.index');
    }

    public function downloadAlbums(DownloadAlbumRequest $request, FlickrService $service)
    {
        $albums = $service->downloadAlbums($request->input('url'));

        $messages = [];

        foreach ($albums as $album) {
            $messages[] = [
                'message' => sprintf(
                    'Downloading Albumid <strong>%s</strong> from NSID %s',
                    $album['id'],
                    $album['owner']
                ),
                'type' => 'info'
            ];
        }

        session()->flash('messages', $messages);

        return response()->view('pages.flickr.index');
    }
}
