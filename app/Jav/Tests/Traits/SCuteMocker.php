<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\SCuteCrawler;
use App\Jav\Services\SCuteService;
use Jooservices\XcrawlerClient\Response\DomResponse;

trait SCuteMocker
{
    protected SCuteCrawler $crawler;

    protected function loadSCuteMocker()
    {
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('contents', ['page' => 1])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'SCute/contents.html'));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('item', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'SCute/item.html'));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'SCute/item.html'));

        $this->service = $this->getService();
        $this->crawler = app(SCuteCrawler::class);
    }

    public function getService(): SCuteService
    {
        app()->instance(SCuteCrawler::class, new SCuteCrawler($this->xcrawlerMocker));

        return app(SCuteService::class);
    }
}
