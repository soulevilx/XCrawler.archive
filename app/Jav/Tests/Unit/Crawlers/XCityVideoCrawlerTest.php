<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityVideoMocker;

class XCityVideoCrawlerTest extends JavTestCase
{
    use XCityVideoMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadXCityVideoMocker();
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

    public function testGetItemNoGenres()
    {
        foreach ([0] as $avodId) {
            $item = $this->crawler->getItem('/avod/detail/', ['id' => $avodId]);
            $this->assertEmpty($item->actresses);
        }
    }

    public function testGetItemFailed()
    {
        $this->assertNull($this->crawler->getItem('/avod/detail/', ['id' => 999]));
    }
}
