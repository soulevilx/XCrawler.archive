<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\XCityIdolCrawler;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

trait XCityIdolMocker
{
    protected XCityIdolCrawler $crawler;

    protected function loadXCityIdolMocker()
    {
        $this->mocker
            ->shouldReceive('get')
            ->with('idol/', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idol.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with('idol/', ['kana' =>'%E3%81%82'])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idols.html'))
        ;

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
