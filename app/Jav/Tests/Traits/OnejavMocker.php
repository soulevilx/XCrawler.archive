<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Models\Onejav;
use Carbon\Carbon;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

trait OnejavMocker
{
    protected OnejavCrawler $crawler;

    protected function loadOnejavMock()
    {
        $now = Carbon::now()->format(Onejav::DAILY_FORMAT);
        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->with('invalid_date', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_date.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with('failed', [])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)))
        ;

        // New
        $this->mocker
            ->shouldReceive('get')
            ->with('new', ['page' => 1])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('new', ['page' => 2])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_2.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('new', ['page' => 3])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_3.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('new', ['page' => 4])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_4.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('new', ['page' => 5])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_5.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with($now, [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 2])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_2.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 3])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_3.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 4])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_4.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 5])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_5.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with('popular', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('popular', ['page' => 2])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular_page_2.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('popular', ['page' => 3])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular_page_3.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('popular', ['page' => 4])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular_page_4.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('popular', ['page' => 5])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular_page_5.html'))
        ;

        app()->instance(XCrawlerClient::class, $this->mocker);
        $this->crawler = app(OnejavCrawler::class);
    }
}
