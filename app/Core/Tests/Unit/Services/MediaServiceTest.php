<?php

namespace App\Core\Tests\Unit\Services;

use App\Core\Services\MediaService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaServiceTest extends TestCase
{
    public function testDownload()
    {
        Storage::fake('downloads');
        $service = app(MediaService::class);
        $clientMocker = \Mockery::mock(Client::class);
        $clientMocker->shouldReceive('request')
            ->andReturn(new Response());
        app()->instance(Client::class, $clientMocker);
        $url = $this->faker->url;

        $service->download('fake', $url);

        Storage::disk('downloads')->assertExists('fake' . '/' .basename($url));
    }
}
