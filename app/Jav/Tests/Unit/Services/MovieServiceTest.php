<?php

namespace App\Jav\Tests\Unit\Services;

use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use App\Jav\Services\Movie\MovieService;
use App\Jav\Tests\JavTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

class MovieServiceTest extends JavTestCase
{
    public function testRequestDownload()
    {
        $this->mocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021.html'));
        app()->instance(XCrawlerClient::class, $this->mocker);

        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')
            ->andReturn(new Response());
        app()->instance(Client::class, $client);

        $movie = Movie::factory()->create([
            'dvd_id' => 'WAAA-088',
        ]);
        $service = app(MovieService::class);
        $service->requestDownload($movie);

        $this->assertDatabaseHas('request_downloads', [
            'model_id' => $movie->id,
            'model_type' => $movie->getMorphClass(),
        ]);


        Onejav::factory()->create([
            'dvd_id' => $movie->dvd_id,
        ]);

        $this->assertNull($movie->refresh()->requestDownload);
    }
}