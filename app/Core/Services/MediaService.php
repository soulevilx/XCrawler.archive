<?php

namespace App\Core\Services;

use App\Core\Services\Facades\Application;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;

class MediaService
{
    private string $dir;

    public function __construct()
    {
        $this->dir = Application::getSetting('core', 'download_dir');
    }

    public function download(string $service, string $url)
    {
        $toDir = storage_path($this->dir) . '/' . $service;

        if (!File::exists($toDir)) {
            File::makeDirectory($toDir, 0755, true);
        }

        $filePath = $toDir . '/' . basename($url);

        $file = fopen($filePath, 'wb');

        $response = app(Client::class)->request(
            'GET',
            $url,
            [
                'sink' => $file,
                'base_uri' => Application::getSetting($service, 'base_url'),
            ]
        );

        return $response->getStatusCode() === 200;
    }
}
