<?php

namespace App\Jav\Tests\Unit\Services;

use App\Core\Models\State;
use App\Core\Services\ApplicationService;
use App\Jav\Events\MovieCreated;
use App\Jav\Models\Movie;
use App\Jav\Services\R18Service;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\R18Mocker;
use Illuminate\Support\Facades\Event;

/**
 * @internal
 * @coversNothing
 */
class R18ServiceTest extends JavTestCase
{
    use R18Mocker;

    protected R18Service $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadR18Mocker();
        $this->service = app(R18Service::class);
    }

    public function testCreateR18()
    {
        Event::fake([MovieCreated::class]);
        $r18Url = $this->faker->unique->url;
        $r18 = $this->service->setAttributes([
            'url' => $r18Url,
            'cover' => $this->faker->unique->url,
            'title' => $this->faker->unique->title,
            'release_date' => $this->faker->date,
            'runtime' => $this->faker->numerify,
            'director' => $this->faker->name,
            'studio' => $this->faker->unique->name,
            'maker' => $this->faker->unique->name,
            'label' => $this->faker->unique->name,
            'channels' => [
                $this->faker->unique->name,
                $this->faker->unique->name,
                $this->faker->unique->name,
            ],
            'content_id' => $this->faker->uuid,
            'dvd_id' => $this->faker->uuid,
            'series' => $this->faker->title,
            'languages' => $this->faker->languageCode,
            'sample' => [
                $this->faker->unique->url,
                $this->faker->unique->url,
                $this->faker->unique->url,
            ],
            'images' => [
                $this->faker->unique->url,
                $this->faker->unique->url,
                $this->faker->unique->url,
            ],
            'gallery' => [
                $this->faker->unique->url,
                $this->faker->unique->url,
                $this->faker->unique->url,
            ],
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
            'state_code' => State::STATE_INIT,
        ])->create();

        $this->assertDatabaseHas('r18', [
            'url' => $r18Url,
        ]);

        $r18->update([
            'state_code' => State::STATE_COMPLETED,
        ]);

        $this->assertInstanceOf(Movie::class, $r18->movie);
        $this->assertEquals($r18->dvd_id, $r18->movie->dvd_id);
        $this->assertEquals($r18->genres, $r18->movie->genres->pluck('name')->toArray());
        $this->assertEquals($r18->performers, $r18->movie->performers->pluck('name')->toArray());
        $this->assertFalse($r18->movie->is_downloadable);

        Event::assertDispatched(MovieCreated::class, function ($event) use ($r18) {
            return $r18->movie->is($event->movie);
        });
    }

    public function testRelease()
    {
        ApplicationService::setConfig('r18', 'total_pages', 2);
        $items = $this->service->release();

        $this->assertEquals(30, $items->count());

        $this->assertDatabaseCount('r18', $items->count());

        $this->assertEquals(2, ApplicationService::getConfig('r18', 'current_page'));
    }

    public function testReleaseAtEndOfPages()
    {
        ApplicationService::setConfig('r18', 'total_pages', 2);
        $this->service->release();
        $this->assertEquals(2, ApplicationService::getConfig('r18', 'current_page'));

        $this->service->release();
        $this->assertEquals(1, ApplicationService::getConfig('r18', 'current_page'));
    }
}