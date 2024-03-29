<?php

namespace App\Jav\Tests\Unit\Services;

use App\Jav\Models\State;
use App\Core\Services\Facades\Application;
use App\Jav\Events\MovieCreated;
use App\Jav\Events\R18\RefetchFailed;
use App\Jav\Models\Movie;
use App\Jav\Models\R18;
use App\Jav\Services\R18Service;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\R18Mocker;
use Illuminate\Support\Facades\Event;
use Jooservices\XcrawlerClient\Response\DomResponse;

class R18ServiceTest extends JavTestCase
{
    use R18Mocker;

    protected R18Service $service;

    public function setUp(): void
    {
        parent::setUp();

        Application::setSetting(R18Service::SERVICE_NAME, 'release_total_pages', 2);
        Application::setSetting(R18Service::SERVICE_NAME, 'release_current_page', 1);
    }

    public function testCreateR18()
    {
        Event::fake([MovieCreated::class]);
        $r18Url = $this->faker->unique->url;
        $r18 = $this->service->create([
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
        ]);

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
        $this->assertFalse($r18->movie->isDownloadable());

        Event::assertDispatched(MovieCreated::class, function ($event) use ($r18) {
            return $r18->movie->is($event->movie);
        });
    }

    public function testRelease()
    {
        Application::setSetting(R18Service::SERVICE_NAME, 'release_current_page', 1);

        $items = $this->service->release();

        $this->assertEquals(30, $items->count());
        $this->assertDatabaseCount('r18', $items->count());

        $this->assertEquals(2, Application::getSetting('r18', 'release_current_page'));
    }

    public function testReleaseUpdate()
    {
        Application::setSetting('r18', 'release_total_pages', 3);
        $items = $this->service->release();

        $this->assertEquals(30, $items->count());
        $this->assertDatabaseCount('r18', $items->count());
        $this->assertEquals(2, Application::getSetting('r18', 'release_current_page'));

        $r18Item = R18::limit(1)->first();
        $r18Item->setState(State::STATE_FAILED);
        $this->service->release();

        // This item was failed before
        $this->assertEquals(State::STATE_FAILED, $r18Item->refresh()->state_code);
    }

    public function testReleaseAtEndOfPages()
    {

        $this->service->release();
        $this->assertEquals(2, Application::getSetting('r18', 'release_current_page'));

        $this->service->release();
        $this->assertEquals(1, Application::getSetting('r18', 'release_current_page'));
    }

    public function testReleaseFailed()
    {
        Application::setSetting('r18', 'release_current_page', 10);
        $this->xcrawlerMocker = $this->getClientMock();
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with(R18::MOVIE_LIST_URL.'/page=10', [])
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)));
        $this->getService()->release();
        $this->assertDatabaseCount('r18', 0);
        $this->assertEquals(10, Application::getSetting('r18', 'release_current_page'));
    }

    public function testDaily()
    {
        $this->service->daily();
        $this->assertDatabaseCount('r18', 30);
    }

    public function testDailyFailed()
    {
        $this->xcrawlerMocker = $this->getClientMock();
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)));
        $this->getService()->daily();
        $this->assertDatabaseCount('r18', 0);
    }

    public function testRefetch()
    {
        $r18 = R18::factory()->create([
            'content_id' => 'rki00506',
        ]);

        $this->service->refetch($r18);
        $this->assertEquals('RKI-506', $r18->refresh()->dvd_id);
    }

    public function testRefetchFailed()
    {
        Event::fake([RefetchFailed::class]);
        $r18 = R18::factory()->create([
            'content_id' => '0',
        ]);

        $this->service->refetch($r18);

        Event::assertDispatched(RefetchFailed::class, function ($event) use ($r18) {
            return $event->model->is($r18);
        });
    }
}
