<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\XCityIdolCrawler;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

trait XCityIdolMocker
{
    protected XCityIdolCrawler $crawler;

    protected $kanas = ['あ', 'か', 'さ', 'た', 'な', 'は', 'ま', 'や', 'ら', 'わ'];

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

        foreach ([5750, 7794, 12519, 13125, 16821] as $idol) {
            $this->mocker
                ->shouldReceive('get')
                ->with('idol/detail/'.$idol, [])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idol_detail_'.$idol.'.html'))
            ;
        }

        app()->instance(XCrawlerClient::class, $this->mocker);
        $this->crawler = app(XCityIdolCrawler::class);
    }
}
