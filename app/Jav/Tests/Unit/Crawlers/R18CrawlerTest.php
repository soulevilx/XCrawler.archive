<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Models\R18;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\R18Mocker;

/**
 * @internal
 * @coversNothing
 */
class R18CrawlerTest extends JavTestCase
{
    use R18Mocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadR18Mocker();
    }

    public function testGetLinks()
    {

        $items = $this->crawler->getItemLinks(R18::MOVIE_LIST_URL);

        $this->assertEquals(
            json_decode($this->getFixture('R18/movie_list.json'), true),
            $items->toArray()
        );
    }

    public function testGetPages()
    {

        $pages = $this->crawler->getPages(R18::MOVIE_LIST_URL);

        $this->assertEquals(1667, $pages);
    }

    public function testGetItem()
    {

        $item = $this->crawler->getItem('api/v4f/contents/rki00506');

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
    }
}
