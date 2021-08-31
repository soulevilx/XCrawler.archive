<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\XCityIdolCrawler;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

trait XCityIdolMocker
{
    protected XCityIdolCrawler $crawler;

    protected array $kanas = ['あ', 'か', 'さ', 'た', 'な', 'は', 'ま', 'や', 'ら', 'わ'];
    protected array $idolIds = [5750, 7794, 12519, 13125, 16821];

    protected function loadXCityIdolMocker()
    {
        $this->mocker
            ->shouldReceive('get')
            ->with('idol/', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idol.html'))
        ;

        foreach ($this->kanas as $kana) {
            $this->mocker
                ->shouldReceive('get')
                ->with('idol/', ['kana' => $kana])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idols.html'))
            ;

            $this->mocker
                ->shouldReceive('get')
                ->with('idol/', ['kana' => $kana, 'page' => 1])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idols.html'))
            ;
            $this->mocker
                ->shouldReceive('get')
                ->with('idol/', ['kana' => $kana, 'page' => 2])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idols.html'))
            ;
        }

        foreach ($this->idolIds as $idolId) {
            $this->mocker
                ->shouldReceive('get')
                ->with('idol/detail/'.$idolId, [])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idol_detail_'.$idolId.'.html'))
            ;
        }

        $this->mocker
            ->shouldReceive('get')
            ->with('idol/detail/999', [])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)))
        ;

        app()->instance(XCrawlerClient::class, $this->mocker);
        $this->crawler = app(XCityIdolCrawler::class);
    }
}
