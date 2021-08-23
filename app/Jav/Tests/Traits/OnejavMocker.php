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

        for ($index = 1; $index <= 5; ++$index) {
            $fileName = 1 === $index ? 'Onejav/july_22_2021.html' : 'Onejav/july_22_2021_page_'.$index.'.html';
            $this->mocker
                ->shouldReceive('get')
                ->with('new', ['page' => $index])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), $fileName))
            ;

            if (1 === $index) {
                $this->mocker
                    ->shouldReceive('get')
                    ->with($now, [])
                    ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), $fileName))
                ;
                $this->mocker
                    ->shouldReceive('get')
                    ->with('popular', [])
                    ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular.html'))
                ;
            } else {
                $this->mocker
                    ->shouldReceive('get')
                    ->with($now, ['page' => $index])
                    ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), $fileName))
                ;
                $this->mocker
                    ->shouldReceive('get')
                    ->with('popular', ['page' => $index])
                    ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular_page_'.$index.'.html'))
                ;
            }
        }

        app()->instance(XCrawlerClient::class, $this->mocker);
        $this->crawler = app(OnejavCrawler::class);
    }
}
