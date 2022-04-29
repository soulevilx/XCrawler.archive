<?php

namespace App\Jav\Tests\Unit\Services;

use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\SCuteCrawler;
use App\Jav\Events\SCute\SCuteCompleted;
use App\Jav\Models\SCute;
use App\Jav\Services\SCuteService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\SCuteMocker;
use Illuminate\Support\Facades\Event;
use Jooservices\XcrawlerClient\Response\DomResponse;

class SCuteServiceTest extends JavTestCase
{
    use SCuteMocker;

    protected SCuteService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadSCuteMocker();
    }

    public function testRelease()
    {
        $this->assertEquals(0, Application::getInt(SCuteService::SERVICE_NAME, 'current_page'));
        $this->assertEquals(121, Application::getInt(SCuteService::SERVICE_NAME, 'total_pages'));

        $this->service->release();

        $this->assertEquals(2, Application::getInt(SCuteService::SERVICE_NAME, 'current_page'));
        $this->assertDatabaseCount('scutes', 31);
    }

    public function testReleaseFailed()
    {
        $this->assertEquals(0, Application::getInt(SCuteService::SERVICE_NAME, 'current_page'));
        $this->assertEquals(121, Application::getInt(SCuteService::SERVICE_NAME, 'total_pages'));

        $mocker = $this->getClientMock();
        $mocker
            ->shouldReceive('get')
            ->andReturn($this->getErrorMockedResponse(new DomResponse()));

        app()->instance(SCuteCrawler::class, new SCuteCrawler($mocker));

        $service = app(SCuteService::class);
        $service->release();

        $this->assertEquals(1, Application::getInt(SCuteService::SERVICE_NAME, 'current_page'));
        $this->assertEquals(121, Application::getInt(SCuteService::SERVICE_NAME, 'total_pages'));
    }

    public function testReleaseAtEndOfPages()
    {
        Application::setSetting(SCuteService::SERVICE_NAME, 'current_page', 121);
        $this->service->release();

        $this->assertEquals(1, Application::getInt(SCuteService::SERVICE_NAME, 'current_page'));
    }

    public function testItemFailed()
    {
        Event::fake(SCuteCompleted::class);

        $model = SCute::factory()->create();

        $mocker = $this->getClientMock();
        $mocker
            ->shouldReceive('get')
            ->andReturn($this->getErrorMockedResponse(app(DomResponse::class)));
        app()->instance(SCuteCrawler::class, new SCuteCrawler($mocker));
        $service = app(SCuteService::class);

        $this->assertNull($service->item($model));
        Event::assertNotDispatched(SCuteCompleted::class);
    }
}
