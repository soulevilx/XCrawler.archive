<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Models\Onejav;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\HasOnejav;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * @internal
 * @coversNothing
 */
class OnejavCrawlerTest extends JavTestCase
{
    use HasOnejav;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadOnejavMock();
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
