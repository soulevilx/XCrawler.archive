<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Services\XCityIdolService;
use Jooservices\XcrawlerClient\Response\DomResponse;

trait XCityIdolMocker
{
    protected XCityIdolCrawler $crawler;

    protected array $kanas = ['あ', 'か', 'さ', 'た', 'な', 'は', 'ま', 'や', 'ら', 'わ'];
    protected array $idolIds = [5750, 7794, 12519, 13125, 16821, 14387, 11924, 8636];

    protected function loadXCityIdolMocker()
    {
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('idol/')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idol.html'));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('idol/', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idol.html'));

        foreach ($this->kanas as $kana) {
            $this->xcrawlerMocker
                ->shouldReceive('get')
                ->with('idol/', ['kana' => $kana])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idols.html'));

            $this->xcrawlerMocker
                ->shouldReceive('get')
                ->with('idol/', ['kana' => $kana, 'page' => 1])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idols.html'));
            $this->xcrawlerMocker
                ->shouldReceive('get')
                ->with('idol/', ['kana' => $kana, 'page' => 2])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idols.html'));
            $this->xcrawlerMocker
                ->shouldReceive('get')
                ->with('idol/', ['kana' => $kana, 'page' => 3])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idols.html'));
        }

        foreach ($this->idolIds as $idolId) {
            $this->xcrawlerMocker
                ->shouldReceive('get')
                ->with('idol/detail/' . $idolId, [])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idol_detail_' . $idolId . '.html'));
        }

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('idol/detail/999', [])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)));

        $this->service = $this->getService();
        $this->crawler = new XCityIdolCrawler($this->xcrawlerMocker);
    }

    protected function getService(): XCityIdolService
    {
        app()->instance(XCityIdolCrawler::class, new XCityIdolCrawler($this->xcrawlerMocker));

        return app(XCityIdolService::class);
    }
}
