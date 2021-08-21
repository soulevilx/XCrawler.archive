<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Models\Onejav;
use App\Jav\Tests\JavTestCase;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;
use Mockery\MockInterface;

/**
 * @internal
 * @coversNothing
 */
class OnejavCrawlerTest extends JavTestCase
{
    protected MockInterface $mocker;

    private OnejavCrawler $crawler;

    public function setUp(): void
    {
        parent::setUp();

        $now = Carbon::now()->format(Onejav::DAILY_FORMAT);

        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->with('invalid_date', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_date.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with('failed', [])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with($now, [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 2])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_2.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 3])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_3.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 4])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_4.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with($now, ['page' => 5])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_5.html'))
        ;

        $this->mocker
            ->shouldReceive('get')
            ->with('/popular', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('/popular', ['page' => 2])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular_page_2.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('/popular', ['page' => 3])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular_page_3.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('/popular', ['page' => 4])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular_page_4.html'))
        ;
        $this->mocker
            ->shouldReceive('get')
            ->with('/popular', ['page' => 5])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/popular_page_5.html'))
        ;

        app()->instance(XCrawlerClient::class, $this->mocker);
        $this->crawler = app(OnejavCrawler::class);
    }

    public function testGetItemsOnPage()
    {
        $items = $this->crawler->getItems(Carbon::now()->format(Onejav::DAILY_FORMAT));

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

    public function testGetItemsFailed()
    {
        $items = $this->crawler->getItems('failed');
        $this->assertTrue($items->isEmpty());
    }

    public function testGetItemsOnPageWithInvalidDate()
    {
        $items = $this->crawler->getItems('invalid_date');

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
        $items = $this->crawler->daily();
        $this->assertEquals(42, $items->count());
    }

    public function testGetPopular()
    {
        $items = $this->crawler->popular();
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
