<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Core\Client;
use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Models\Onejav;
use App\Jav\Tests\JavTestCase;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Jooservices\XcrawlerClient\XCrawlerClient;

/**
 * @internal
 * @coversNothing
 */
class OnejavCrawlerTest extends JavTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $now = Carbon::now()->format(Onejav::DAILY_FORMAT);
        $this->mocker
            ->shouldReceive('get')
            ->with('invalid_date', [])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/july_22_2021_date.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with($now, [])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/july_22_2021.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 2])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/july_22_2021_page_2.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 3])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/july_22_2021_page_3.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 4])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/july_22_2021_page_4.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 5])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/july_22_2021_page_5.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with('/popular', [])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/popular.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('/popular', ['page' => 2])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/popular_page_2.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('/popular', ['page' => 3])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/popular_page_3.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('/popular', ['page' => 4])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/popular_page_4.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('/popular', ['page' => 5])
            ->andReturn($this->getSuccessfulMockedResponse('Onejav/popular_page_5.html'))
        ;

        app()->instance(XCrawlerClient::class, $this->mocker);
    }

    public function testGetItemsOnPage()
    {
        $crawler = app(OnejavCrawler::class);
        $items = $crawler->getItems(Carbon::now()->format('Y/m/d'));

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals(10, $items->count());

        $data = json_decode($this->getFixture('Onejav/july_22_2021.json'));
        foreach ($items as $index => $item) {
            foreach ($item as $property => $value) {
                if (is_object($value) && property_exists($data[$index]->{$property}, 'date')) {
                    $date = Carbon::createFromFormat('Y-m-d H:i:s.u', $data[$index]->{$property}->date);
                    $this->assertEquals($value->format('M j, Y'), $date->toFormattedDateString());

                    continue;
                }
                $this->assertEquals($value, $data[$index]->{$property});
            }

            $this->assertKey($item->getArrayCopy());
        }
    }

    public function testGetItemsOnPageWithInvalidDate()
    {
        $crawler = app(OnejavCrawler::class);
        $items = $crawler->getItems('invalid_date');

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals(10, $items->count());

        $data = json_decode($this->getFixture('Onejav/july_22_2021.json'));
        foreach ($items as $index => $item) {
            foreach ($item as $property => $value) {
                if (is_object($value) && property_exists($data[$index]->{$property}, 'date')) {
                    if (0 === $index) {
                        $this->assertNull($value);

                        continue;
                    }

                    $date = Carbon::createFromFormat('Y-m-d H:i:s.u', $data[$index]->{$property}->date);
                    $this->assertEquals($value->format('M j, Y'), $date->toFormattedDateString());

                    continue;
                }

                if (0 === $index && 'date' === $property) {
                    $this->assertNull($value);

                    continue;
                }

                $this->assertEquals($value, $data[$index]->{$property});
            }
        }

        $this->assertKey($item->getArrayCopy());
    }

    public function testGetDaily()
    {
        $crawler = app(OnejavCrawler::class);
        $items = $crawler->daily();
        $this->assertEquals(42, $items->count());
    }

    public function testGetPopular()
    {
        $crawler = app(OnejavCrawler::class);
        $items = $crawler->popular();
        $this->assertEquals(50, $items->count());
    }

    private function assertKey(array $item)
    {
        $this->assertArrayHasKey('url', $item);
        $this->assertArrayHasKey('cover', $item);
        $this->assertArrayHasKey('dvd_id', $item);
        $this->assertArrayHasKey('size', $item);
        $this->assertArrayHasKey('date', $item);
        $this->assertArrayHasKey('genres', $item);
        $this->assertArrayHasKey('description', $item);
        $this->assertArrayHasKey('performers', $item);
        $this->assertArrayHasKey('torrent', $item);
    }
}
