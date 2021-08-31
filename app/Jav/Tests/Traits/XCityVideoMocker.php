<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\XCityVideoCrawler;
use App\Jav\Models\XCityVideo;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

trait XCityVideoMocker
{
    protected XCityVideoCrawler $crawler;

    protected function loadXCityVideoMocker()
    {
        $this->mocker
            ->shouldReceive('get')
            ->with(XCityVideo::INDEX_URL, ['num' => XCityVideo::PER_PAGE])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/avod_list.html'))
        ;
        foreach ([147028, 147243, 150786] as $avodId) {
            $this->mocker
                ->shouldReceive('get')
                ->with('/avod/detail/', ['id' => $avodId])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/avod_detail_'.$avodId.'.html'))
            ;
        }

        $this->mocker
            ->shouldReceive('get')
            ->with('/avod/detail/', ['id' => 999])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with(XCityVideo::INDEX_URL, ['num' => 999])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)))
        ;

        app()->instance(XCrawlerClient::class, $this->mocker);
        $this->crawler = app(XCityVideoCrawler::class);
    }
}
