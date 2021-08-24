<?php

namespace App\Jav\Tests\Unit\Services;

use App\Core\Services\ApplicationService;
use App\Jav\Events\MovieCreated;
use App\Jav\Models\Movie;
use App\Jav\Services\OnejavService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\OnejavMocker;
use Illuminate\Support\Facades\Event;

/**
 * @internal
 * @coversNothing
 */
class OnejavServiceTest extends JavTestCase
{
    use OnejavMocker;

    protected OnejavService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadOnejavMock();
        $this->service = app(OnejavService::class);
    }

    public function testCreateOnejav()
    {
        Event::fake([MovieCreated::class]);
        $onejav = $this->service->setAttributes([
            'url' => 'https://onejav.com/actress/Arina%20Hashimoto',
            'cover' => $this->faker->unique->url,
            'dvd_id' => $this->faker->unique->uuid,
            'size' => $this->faker->randomFloat(2, 10, 20),
            'date' => $this->faker->date,
            'genres' => [
                $this->faker->unique->word,
                $this->faker->unique->word,
                $this->faker->unique->word,
            ],
            'performers' => [
                $this->faker->unique->name,
                $this->faker->unique->name,
                $this->faker->unique->name,
            ],
            'description' => $this->faker->text,
            'torrent' => $this->faker->unique->url,
        ])->create();

        $this->assertDatabaseHas('onejav', [
            'url' => 'https://onejav.com/actress/Arina%20Hashimoto',
        ]);

        $this->assertInstanceOf(Movie::class, $onejav->movie);
        $this->assertEquals($onejav->dvd_id, $onejav->movie->dvd_id);
        $this->assertEquals($onejav->genres, $onejav->movie->genres->pluck('name')->toArray());
        $this->assertEquals($onejav->performers, $onejav->movie->performers->pluck('name')->toArray());
        $this->assertTrue($onejav->movie->is_downloadable);

        Event::assertDispatched(MovieCreated::class, function ($event) use ($onejav) {
            return $onejav->movie->is($event->movie);
        });
    }

    public function testDaily()
    {
        $items = $this->service->daily();
        $this->assertEquals(42, $items->count());

        $this->assertDatabaseCount('onejav', $items->count());
        $this->assertDatabaseCount('movies', $items->count());
        $this->assertDatabaseCount('wordpress_posts', $items->count());
    }

    /**
     * @covers \App\Jav\Services\OnejavService
     */
    public function testRelease()
    {
        ApplicationService::setConfig('onejav', 'total_pages', 2);
        $items = $this->service->release();

        $this->assertEquals(10, $items->count());

        $this->assertDatabaseCount('onejav', $items->count());
        $this->assertDatabaseCount('movies', $items->count());
        $this->assertDatabaseCount('wordpress_posts', $items->count());

        $this->assertEquals(2, ApplicationService::getConfig('onejav', 'current_page'));
    }

    public function testReleaseAtEndOfPages()
    {
        ApplicationService::setConfig('onejav', 'total_pages', 2);
        $this->service->release();
        $this->assertEquals(2, ApplicationService::getConfig('onejav', 'current_page'));

        $this->service->release();
        $this->assertEquals(1, ApplicationService::getConfig('onejav', 'current_page'));
    }
}
