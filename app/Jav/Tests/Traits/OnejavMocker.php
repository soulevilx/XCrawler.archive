<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Models\Onejav;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

trait OnejavMocker
{
    protected OnejavCrawler $crawler;

    protected function loadOnejavMock()
    {
        $now = Carbon::now()->format(Onejav::DAILY_FORMAT);

        $this->invalid();

        for ($index = 1; $index <= 5; $index++) {
            $this->mockResponse('new', $index);
            $this->mockResponse('popular', $index);
            $this->mockResponse('search/test', $index);

            $this->mocker
                ->shouldReceive('get')
                ->with($now, $index === 1 ? [] : ['page' => $index])
                ->andReturn(
                    $this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_' . $index . '.html')
                );
        }

        app()->instance(XCrawlerClient::class, $this->mocker);
        $this->crawler = app(OnejavCrawler::class);
    }

    private function invalid()
    {
        $this->mocker
            ->shouldReceive('get')
            ->with('invalid_date', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_date.html'));

        $this->mocker
            ->shouldReceive('get')
            ->with('failed', [])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)));
    }

    private function mockResponse(string $name, ?int $page = null)
    {
        $fixtureFile = 'Onejav/' . Str::slug(Str::replace('/', '_', $name), '_') . '_page_' . $page . '.html';

        $this->mocker
            ->shouldReceive('get')
            ->with($name, $page === 1 ? [] : ['page' => $page])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), $fixtureFile));

        $this->mocker
            ->shouldReceive('get')
            ->with($name, ['page' => $page])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), $fixtureFile));
    }
}
