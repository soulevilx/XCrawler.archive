<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Jav\Crawlers\R18Crawler;
use App\Jav\Events\MovieCreated;
use App\Jav\Jobs\R18\ItemFetch;
use App\Jav\Models\Movie;
use App\Jav\Models\R18;
use App\Jav\Services\Movie\MovieService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\R18Mocker;
use Illuminate\Support\Facades\Event;
use Jooservices\XcrawlerClient\Response\JsonResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

class R18ItemFetchTest extends JavTestCase
{
    use R18Mocker;

    private MovieService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->mocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(JsonResponse::class), 'R18/item.json'));

        app()->instance(XCrawlerClient::class, $this->mocker);

        $this->crawler = app(R18Crawler::class);
        $this->service = app(MovieService::class);
    }

    public function testItemFetch()
    {
        $model = R18::factory()->create();
        ItemFetch::dispatch($model);

        $model->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $model->state_code);
        $this->assertEquals('RKI-506', $model->dvd_id);

        $this->assertInstanceOf(Movie::class, $model->movie);
    }

    public function testItemFetchWithDvdIdIsNull()
    {
        /**
         * R18 with content_id only
         */
        Event::fake([MovieCreated::class]);
        $model = R18::factory()->create(['dvd_id' => null]);

        $movie = $this->service->create($model);

        $this->assertNull($movie->dvd_id);
        $this->assertTrue($model->movie->is($movie));

        Event::assertDispatched(MovieCreated::class, function ($event) use ($movie) {
            return $event->movie->is($movie);
        });

        /**
         * Update title will update to movie
         */
        $model->update([
            'title' => $this->faker->title,
        ]);
        $movie = $this->service->create($model);

        $this->assertEquals($model->getName(), $movie->name);
        Event::assertDispatchedTimes(MovieCreated::class);

        /**
         * Update R18 with DVDID
         */
        $model->update([
            'dvd_id' => $this->faker->uuid,
        ]);
        $movie = $this->service->create($model);
        $this->assertEquals($model->dvd_id, $movie->dvd_id);
        Event::assertDispatchedTimes(MovieCreated::class);
        $this->assertEquals(1, Movie::count());
    }

    public function testItemFetchWithMovieContentId()
    {
        $originalMovie = Movie::factory()->create();
        Event::fake([MovieCreated::class]);

        /**
         * Case 1 : Try to create movie with matched content_id
         */
        $model = R18::factory()->create(['content_id' => $originalMovie->content_id]);
        $movie = $this->service->create($model);

        $this->assertTrue($model->movie->is($originalMovie));
        $this->assertTrue($movie->is($originalMovie));
        $this->assertEquals($originalMovie->refresh()->name, $model->getName());

        Event::assertNotDispatched(MovieCreated::class, function ($event) use ($movie) {
            return $event->movie->is($movie);
        });
    }

    public function testItemFetchWithMovieDvdId()
    {
        $originalMovie = Movie::factory()->create();
        Event::fake([MovieCreated::class]);

        /**
         * Case 1 : Try to create movie with matched dvd_id but not content id
         */
        $model = R18::factory()->create([
            'content_id' => $this->faker->uuid,
            'dvd_id' => $originalMovie->dvd_id
        ]);

        $movie = $this->service->create($model);

        $this->assertTrue($model->movie->is($originalMovie));
        $this->assertTrue($movie->is($originalMovie));
        $this->assertEquals($originalMovie->refresh()->name, $model->getName());

        Event::assertNotDispatched(MovieCreated::class, function ($event) use ($movie) {
            return $event->movie->is($movie);
        });
    }
}
