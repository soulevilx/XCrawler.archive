<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;
use Jooservices\XcrawlerClient\Response\DomResponse;

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
        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->with('idol/')
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)));

        $crawler = new XCityIdolCrawler($this->mocker);
        $this->assertTrue($crawler->getSubPages()->isEmpty());
    }

    public function testGetItem()
    {
        foreach ([5750, 7794, 12519, 13125, 16821] as $idol) {
            $item = $this->crawler->getItem($idol);
            $data = json_decode($this->getFixture('XCity/idol_detail_' . $idol . '.json'), true);
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

        $item = $this->crawler->getItem(8636);
        $this->assertEquals('Makoto Toda', $item->name);
        $this->assertEquals('Making video, Walking', $item->special_skill);
        $this->assertEquals('2018年第30回ピンク映画大賞で新人女優賞受賞 キャッチコピーは「どスケベ小動物」コラムニストとしても活躍中で特に映画評に定評がある', $item->other);
    }

    public function testGetItemWithNoHeight()
    {
        foreach ([14387, 11924] as $idol) {
            $item = $this->crawler->getItem($idol);
            $this->assertNull($item->height);
            $this->assertNull($item->blood_type);
            $this->assertNull($item->city_of_born);
            $this->assertNull($item->special_skill);
            $this->assertNull($item->other);
        }
    }

    public function testGetItemWithoutBirthOfDate()
    {
        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->with('idol/detail/18410', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'XCity/idol_detail_18410.html'));
        $crawler = new XCityIdolCrawler($this->mocker);
        $item = $crawler->getItem(18410);

        $this->assertNull($item->date_of_birth);
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
