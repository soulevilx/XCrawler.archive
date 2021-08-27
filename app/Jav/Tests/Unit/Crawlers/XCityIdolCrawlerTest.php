<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;

/**
 * @internal
 * @coversNothing
 */
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
