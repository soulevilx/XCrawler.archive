<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;


class XCityIdolCrawlerTest extends JavTestCase
{
    use XCityIdolMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadXCityIdolMocker();
    }

    public function testGetSubPages()
    {
        $pages = $this->crawler->getSubPages();

        $this->assertCount(10, $pages);
        $this->assertEquals(json_decode($this->getFixture('XCity/idol.json')), $pages->toArray());
    }

    public function testGetSubPagesFailed()
    {
        $mocker = $this->getClientMock();
        $mocker
            ->shouldReceive('get')
            ->with('idol/', [])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)))
        ;

        app()->instance(XCrawlerClient::class, $mocker);
        $crawler = app(XCityIdolCrawler::class);
        $this->assertTrue($crawler->getSubPages()->isEmpty());
    }

    public function testGetItem()
    {
        foreach ([5750, 7794, 12519, 13125, 16821] as $idol) {
            $item = $this->crawler->getItem($idol);
            $data = json_decode($this->getFixture('XCity/idol_detail_'.$idol.'.json'), true);
            if (13125 === $idol) {
                $this->assertEmpty($item->blood_type);
            }
            foreach ($data as $key => $value) {
                if ('date_of_birth' === $key) {
                    continue;
                }
                $this->assertEquals($item->{$key}, $value);
            }
        }
    }

    public function testGetItemFailed()
    {
        $this->assertNull($this->crawler->getItem(999));
    }

    public function testGetItemLinks()
    {
        $links = $this->crawler->getItemLinks('idol/');
        $this->assertCount(49, $links);
        $this->assertEquals(json_decode($this->getFixture('XCity/links.json')), $links->toArray());
    }

    public function testGetPages()
    {
        $pages = $this->crawler->getPages('idol/', ['kana' => 'あ']);
        $this->assertEquals(112, $pages);
    }
}
