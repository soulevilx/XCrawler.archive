<?php

namespace App\Jav\Tests\Unit\Services;

use App\Core\Services\Facades\Application;
use App\Jav\Events\MovieCreated;
use App\Jav\Events\Onejav\OnejavDailyCompleted;
use App\Jav\Events\Onejav\OnejavDownloadCompleted;
use App\Jav\Events\Onejav\OnejavReleaseCompleted;
use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use App\Jav\Services\OnejavService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\OnejavMocker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Event;
use Jooservices\XcrawlerClient\Response\DomResponse;

class OnejavServiceTest extends JavTestCase
{
    use OnejavMocker;

    protected OnejavService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadOnejavMock();

        Event::fake([
            OnejavDailyCompleted::class,
            OnejavReleaseCompleted::class,
            OnejavDownloadCompleted::class,
        ]);
    }

    public function testCreateOnejav()
    {
        Event::fake([MovieCreated::class]);
        $onejav = $this->service->create([
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
        ]);

        $this->assertDatabaseHas('onejav', [
            'url' => 'https://onejav.com/actress/Arina%20Hashimoto',
        ]);

        $this->assertInstanceOf(Movie::class, $onejav->movie);
        $this->assertEquals($onejav->dvd_id, $onejav->movie->dvd_id);
        $this->assertEquals($onejav->genres, $onejav->movie->genres->pluck('name')->toArray());
        $this->assertEquals($onejav->performers, $onejav->movie->performers->pluck('name')->toArray());
        $this->assertTrue($onejav->movie->isDownloadable());

        Event::assertDispatched(MovieCreated::class, function ($event) use ($onejav) {
            return $onejav->movie->is($event->movie);
        });
    }

    public function testCreateWithDuplicatedUrl()
    {
        $onejav = $this->service->create([
            'url' => 'fake',
            'cover' => $this->faker->unique->url,
            'dvd_id' => 'fake-dvd-id',
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
        ]);

        $this->assertDatabaseHas('onejav', ['url' => 'fake']);

        $this->service->create([
            'url' => 'fake',
            'cover' => $this->faker->unique->url,
            'dvd_id' => 'fake-dvd-id-2',
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
        ]);

        $onejav->refresh();
        $this->assertEquals('fake-dvd-id-2', $onejav->getDvdId());
    }

    public function testCreateOnejavWithExistsMovie()
    {
        Event::fake([MovieCreated::class]);
        Movie::factory()->create([
            'dvd_id' => 'fake',
        ]);

        $this->service->create([
            'url' => 'https://onejav.com/actress/Arina%20Hashimoto',
            'cover' => $this->faker->unique->url,
            'dvd_id' => 'fake',
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
        ]);

        /**
         * This movie already exists we won't dispatch event again
         */
        Event::assertNotDispatched(MovieCreated::class);
    }

    public function testDaily()
    {
        $items = $this->service->daily();
        $totalItems = $items->count();
        $this->assertEquals(42, $totalItems);

        $this->assertDatabaseCount('onejav', $totalItems);
        $this->assertDatabaseCount('movies', $totalItems);
        $this->assertDatabaseCount('genres', 65);
        $this->assertDatabaseCount('performers', 40);

        Event::assertDispatched(OnejavDailyCompleted::class);
    }

    public function testDailyFailed()
    {
        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)));
        $this->service = $this->getService();

        $items = $this->service->daily();
        $this->assertTrue($items->isEmpty());

        $this->assertDatabaseCount('onejav', 0);
        $this->assertDatabaseCount('movies', 0);
        $this->assertDatabaseCount('genres', 0);
        $this->assertDatabaseCount('performers', 0);
    }

    public function testRelease()
    {
        Application::setSetting('onejav', 'total_pages', 2);

        $items = $this->service->release();

        $this->assertEquals(10, $items->count());

        $this->assertDatabaseCount('onejav', $items->count());
        $this->assertDatabaseCount('movies', $items->count());

        $this->assertEquals(2, Application::getSetting('onejav', 'current_page'));
        Event::assertDispatched(OnejavReleaseCompleted::class);
    }

    public function testReleaseAtEndOfPages()
    {
        Application::setSetting('onejav', 'total_pages', 2);
        $this->service->release();

        $this->assertEquals(2, Application::getSetting('onejav', 'current_page'));

        $this->service->release();

        $this->assertEquals(1, Application::getSetting('onejav', 'current_page'));

        Event::assertDispatched(OnejavReleaseCompleted::class);
    }

    public function testItem()
    {
        $onejav = Onejav::factory()->create();

        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->with($onejav->url, [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_1.html'));

        $this->service = $this->getService();
        $this->service->item($onejav);

        $this->assertEquals('WAAA-088', $onejav->refresh()->dvd_id);
    }

    public function testDownload()
    {
        $onejav = Onejav::factory()->create();
        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_1.html'));

        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')
            ->andReturn(new Response());
        app()->instance(Client::class, $client);

        $this->service = $this->getService();

        $this->assertTrue($this->service->download($onejav));

        $this->assertDatabaseHas('downloads', [
            'model_id' => $onejav->id,
            'model_type' => Onejav::class,
        ]);

        Event::assertDispatched(OnejavDownloadCompleted::class, function ($event) use ($onejav) {
            return $event->onejav->is($onejav);
        });

        $this->assertFalse($this->service->download($onejav));
    }

    public function testDownloadFailed()
    {
        $onejav = Onejav::factory()->create();
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')
            ->andReturn(new Response(303));
        app()->instance(Client::class, $client);

        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_1.html'));
        $this->service = $this->getService();

        $this->assertFalse($this->service->download($onejav));
    }
}
