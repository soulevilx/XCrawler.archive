<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\XCityVideoCrawler;
use App\Jav\Models\XCityVideo;
use App\Jav\Services\XCityVideoService;
use Carbon\Carbon;
use Jooservices\XcrawlerClient\Response\DomResponse;

trait XCityVideoMocker
{
    protected XCityVideoCrawler $crawler;

    protected function loadXCityVideoMocker()
    {
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with(XCityVideo::INDEX_URL, ['num' => XCityVideo::PER_PAGE])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/avod_list.html'));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with(XCityVideo::INDEX_URL, ['num' => XCityVideo::PER_PAGE, 'from_date' => '20010101', 'to_date' => '20010102'])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/avod_range.html'));
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with(
                XCityVideo::INDEX_URL,
                ['num' => XCityVideo::PER_PAGE, 'from_date' => '20010101', 'to_date' => '20010102', 'page' => 1]
            )
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/avod_range.html'));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with(XCityVideo::INDEX_URL, ['num' => XCityVideo::PER_PAGE, 'from_date' => Carbon::now()->format('Ymd'), 'to_date' => Carbon::now()->format('Ymd')])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/avod_range.html'));
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with(XCityVideo::INDEX_URL, ['num' => XCityVideo::PER_PAGE, 'from_date' => Carbon::now()->format('Ymd'), 'to_date' => Carbon::now()->format('Ymd'), 'page' => 1])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/avod_range.html'));

        foreach ([147028, 147243, 150786, 162337, 0] as $avodId) {
            $this->xcrawlerMocker
                ->shouldReceive('get')
                ->with('/avod/detail/', ['id' => $avodId])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/avod_detail_'.$avodId.'.html'));
        }

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('/avod/detail/', ['id' => 999])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with(XCityVideo::INDEX_URL, ['num' => 999])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)));

        $this->service = $this->getService();
        $this->crawler = new XCityVideoCrawler($this->xcrawlerMocker);
    }

    public function getService(): XCityVideoService
    {
        app()->instance(XCityVideoCrawler::class, new XCityVideoCrawler($this->xcrawlerMocker));

        return app(XCityVideoService::class);
    }
}
