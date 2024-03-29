<?php

namespace App\Jav\Tests\Unit\Services;

use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Models\Movie;
use App\Jav\Services\Movie\MovieService;
use App\Jav\Tests\JavTestCase;
use Jooservices\XcrawlerClient\Response\DomResponse;

class MovieServiceTest extends JavTestCase
{
    public function testRequestDownload()
    {
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_1.html'));
        app()->instance(OnejavCrawler::class, new OnejavCrawler($this->xcrawlerMocker));

        $movie = Movie::factory()->create([
            'dvd_id' => 'WAAA-088',
        ]);
        $service = app(MovieService::class);
        $service->requestDownload($movie);

        $this->assertDatabaseHas('request_downloads', [
            'model_id' => $movie->id,
            'model_type' => $movie->getMorphClass(),
        ]);
    }

    public function testRequestDownloadExists()
    {
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_1.html'));
        app()->instance(OnejavCrawler::class, new OnejavCrawler($this->xcrawlerMocker));

        $movie = Movie::factory()->create([
            'dvd_id' => 'WAAA-088',
        ]);

        $movie->requestDownload()->create();

        $service = app(MovieService::class);
        $this->assertFalse($service->requestDownload($movie));
    }
}
