<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Crawlers\XCityVideoCrawler;
use App\Jav\Models\XCityVideo;
use App\Jav\Tests\JavTestCase;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

class XCityVideoCrawlerTest extends JavTestCase
{
    protected XCityVideoCrawler $crawler;

    public function setUp(): void
    {
        parent::setUp();

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

    public function testGetItemLinks()
    {
        $this->assertEquals(
            json_decode($this->getFixture('XCity/avod_list.json'), true),
            $this->crawler->getItemLinks()->toArray()
        );
    }

    public function testGetItemLinksFailed()
    {
        $this->assertTrue($this->crawler->getItemLinks(['num' => 999])->isEmpty());
    }

    public function testGetPages()
    {
        $this->assertEquals(68, $this->crawler->getPages());
    }

    public function testGetPagesFailed()
    {
        $this->assertEquals(1, $this->crawler->getPages(['num' => 999]));
    }

    public function testGetItem()
    {
        foreach ([147028, 147243, 150786] as $avodId) {
            $item = $this->crawler->getItem('/avod/detail/', ['id' => $avodId]);
            $data = json_decode($this->getFixture('XCity/avod_detail_'.$avodId.'.json'), true);

            foreach ($data as $key => $value) {
                if ('sales_date' === $key || 'release_date' === $key) {
                    continue;
                }
                $this->assertEquals($item->{$key}, $value);
            }
        }
    }

    public function testGetItemFailed()
    {
        $this->assertNull($this->crawler->getItem('/avod/detail/', ['id' => 999]));
    }
}
