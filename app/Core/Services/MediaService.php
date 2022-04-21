<?php

namespace App\Core\Services;

use App\Core\Services\Facades\Application;
use GuzzleHttp\Client;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    private FilesystemAdapter $storage;

    public function __construct()
    {
        $this->storage = Storage::drive('downloads');
    }

    public function download(string $service, string $url)
    {
        if (!$this->storage->exists($service)) {
            $this->storage->makeDirectory($service);
        }

        $filePath = $this->storage->path($service) . '/' . basename($url);

        $file = fopen($filePath, 'wb');

        $response = app(Client::class)->request(
            'GET',
            $url,
            [
                'sink' => $file,
                'base_uri' => Application::getSetting($service, 'base_url'),
            ]
        );

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        return $service . '/' . basename($url);
    }
}
