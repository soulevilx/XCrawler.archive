<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Models\R18;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\R18Mocker;

class R18CrawlerTest extends JavTestCase
{
    use R18Mocker;

    public function testGetLinks()
    {
        $items = $this->crawler->getItemLinks(R18::MOVIE_LIST_URL);

        $this->assertEquals(
            json_decode($this->getFixture('R18/movie_list.json'), true),
            $items->toArray()
        );
    }

    public function getDaily()
    {
        $items = $this->crawler->getItemLinks('videos/rankings/movies/?type=daily');
        $this->assertEquals(100, $items->count());
    }

    public function getChannels()
    {
        $items = $this->crawler->getItemLinks('videos/channels/s1');
        $this->assertEquals(100, $items->count());

        $items = $this->crawler->getItemLinks('videos/channels/moodyz');
        $this->assertEquals(100, $items->count());
    }

    /**
     * @dataProvider dataProviderGetPages
     * @param string $url
     * @param int    $expectedPages
     * @return void
     */
    public function testGetPages(string $url, int $expectedPages)
    {
        $this->assertEquals($expectedPages, $this->crawler->getPages($url));
    }

    public function dataProviderGetPages()
    {
        return [
            [
                R18::MOVIE_LIST_URL,
                1667,
            ],
            [
                'videos/rankings/movies/?type=daily',
                1,
            ],
            [
                'videos/channels/s1',
                68,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderGetItem
     * @return void
     */
    public function testGetItem(string $id, array $data)
    {
        $item = $this->crawler->getItem($id);

        $this->assertIsArray($item);
        $this->assertArrayHasKey('content_id', $item);
        $this->assertArrayHasKey('dvd_id', $item);
        $this->assertArrayHasKey('title', $item);
        $this->assertArrayHasKey('runtime_minutes', $item);
        $this->assertArrayHasKey('release_date', $item);
        $this->assertArrayHasKey('maker', $item);
        $this->assertArrayHasKey('label', $item);
        $this->assertArrayHasKey('series', $item);
        $this->assertArrayHasKey('categories', $item);
        $this->assertArrayHasKey('actresses', $item);
        $this->assertArrayHasKey('channels', $item);
        $this->assertArrayHasKey('sample', $item);
        $this->assertArrayHasKey('images', $item);
        $this->assertArrayHasKey('detail_url', $item);
        $this->assertArrayHasKey('gallery', $item);
        $this->assertArrayHasKey('director', $item);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $item[$key]);
        }
    }

    public function dataProviderGetItem()
    {
        return [
            [
                'rki00506',
                [],
            ],
            [
                'pfes00054',
                [
                    'content_id' => 'pfes00054',
                    'director' => 'Nao Masaki',
                    'release_date' => '2022-03-25 10:00:11',
                ],
            ],
        ];
    }

    public function testGetItemFailed()
    {
        $this->assertNull($this->crawler->getItem('0'));
    }
}
