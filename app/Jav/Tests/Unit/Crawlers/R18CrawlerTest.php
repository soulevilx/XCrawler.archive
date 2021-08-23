<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Crawlers\R18Crawler;
use App\Jav\Models\R18;
use App\Jav\Tests\JavTestCase;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\Response\JsonResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

/**
 * @internal
 * @coversNothing
 */
class R18CrawlerTest extends JavTestCase
{
    public function testGetLinks()
    {
        $this->mocker
            ->shouldReceive('get')
            ->with(R18::MOVIE_LIST_URL, [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'R18/movie_list.html'))
        ;
        app()->instance(XCrawlerClient::class, $this->mocker);

        $crawler = app(R18Crawler::class);
        $items = $crawler->getItemLinks(R18::MOVIE_LIST_URL);

        $this->assertEquals(
            json_decode($this->getFixture('R18/movie_list.json'), true),
            $items->toArray()
        );
    }

    public function testGetPages()
    {
        $this->mocker
            ->shouldReceive('get')
            ->with(R18::MOVIE_LIST_URL, [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'R18/movie_list.html'))
        ;
        app()->instance(XCrawlerClient::class, $this->mocker);

        $crawler = app(R18Crawler::class);
        $pages = $crawler->getPages(R18::MOVIE_LIST_URL);

        $this->assertEquals(1667, $pages);
    }

    public function testGetItem()
    {
        $this->mocker
            ->shouldReceive('get')
            ->with('api/v4f/contents/rki00506', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(JsonResponse::class), 'R18/item.json'))
        ;
        app()->instance(XCrawlerClient::class, $this->mocker);

        $crawler = app(R18Crawler::class);
        $item = $crawler->getItem('api/v4f/contents/rki00506');

        $this->assertIsArray($item);
        $this->assertArrayHasKey('content_id', $item);
        $this->assertArrayHasKey('dvd_id', $item);
        $this->assertArrayHasKey('title', $item);
        $this->assertArrayHasKey('runtime_minutes', $item);
        $this->assertArrayHasKey('release_date', $item);
        $this->assertArrayHasKey('maker', $item);
        $this->assertArrayHasKey('label', $item);
        $this->assertArrayHasKey('series', $item);
        $this->assertArrayHasKey('categories', $item);
        $this->assertArrayHasKey('actresses', $item);
        $this->assertArrayHasKey('channels', $item);
        $this->assertArrayHasKey('sample', $item);
        $this->assertArrayHasKey('images', $item);
        $this->assertArrayHasKey('detail_url', $item);
        $this->assertArrayHasKey('gallery', $item);
        $this->assertArrayHasKey('director', $item);
    }
}
