<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Services\OnejavService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Jooservices\XcrawlerClient\Response\DomResponse;

trait OnejavMocker
{
    protected OnejavCrawler $crawler;

    protected function loadOnejavMock()
    {
        $now = Carbon::now()->format(OnejavService::DAILY_FORMAT);

        $this->invalid();

        for ($index = 1; $index <= 5; $index++) {
            $this->mockResponse('new', $index);
            $this->mockResponse('popular', $index);
            $this->mockResponse('search/test', $index);

            $this->xcrawlerMocker
                ->shouldReceive('get')
                ->with($now, $index === 1 ? [] : ['page' => $index])
                ->andReturn(
                    $this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_' . $index . '.html')
                );
        }

        // FC
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->withSomeOfArgs('fc')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/fc.html'));

        // Item
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('/torrent/waaa088_1',[])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/item.html'));

        $this->service = $this->getService();
        $this->crawler = new OnejavCrawler($this->xcrawlerMocker);
    }

    private function invalid()
    {
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('invalid_date', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_date.html'));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('failed', [])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)));
    }

    private function mockResponse(string $name, ?int $page = null)
    {
        $fixtureFile = 'Onejav/' . Str::slug(Str::replace('/', '_', $name), '_') . '_page_' . $page . '.html';

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with($name, $page === 1 ? [] : ['page' => $page])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), $fixtureFile));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with($name, ['page' => $page])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), $fixtureFile));
    }

    protected function getService(): OnejavService
    {
        app()->instance(OnejavCrawler::class, new OnejavCrawler($this->xcrawlerMocker));

        return app(OnejavService::class);
    }
}
