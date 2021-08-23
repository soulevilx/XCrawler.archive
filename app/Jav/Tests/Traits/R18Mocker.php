<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\R18Crawler;
use App\Jav\Models\R18;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\Response\JsonResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

trait R18Mocker
{
    protected R18Crawler $crawler;

    protected function loadR18Mocker()
    {
        $this->mocker
            ->shouldReceive('get')
            ->with(R18::MOVIE_LIST_URL, [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'R18/movie_list.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with(R18::MOVIE_LIST_URL.'/page=1', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'R18/movie_list.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with(R18::MOVIE_LIST_URL.'/page=2', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'R18/movie_list.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with('api/v4f/contents/rki00506', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(JsonResponse::class), 'R18/item.json'))
        ;

        app()->instance(XCrawlerClient::class, $this->mocker);

        $this->crawler = app(R18Crawler::class);
    }
}
