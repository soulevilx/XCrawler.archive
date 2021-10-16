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
        $user = $service->urls()->lookupUser($request->input('url'));
        $url = explode('/', $request->input('url'));
        $albumId = end($url);
        session()->flash('message', ['Downloading Albumid <strong>' . $albumId. '</strong> from user ID ' . $user['id'], 'type' => 'info']);
        return response()->view('pages.flickr.index',);
    }
}
