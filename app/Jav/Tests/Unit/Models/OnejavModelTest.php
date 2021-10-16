<?php

namespace App\Jav\Tests\Unit\Models;

use App\Core\Models\State;
use App\Core\Services\ApplicationService;
use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use App\Jav\Notifications\MovieCreatedNotification;
use App\Jav\Tests\JavTestCase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;


class OnejavModelTest extends JavTestCase
{
    public function testModel()
    {
        ApplicationService::setConfig(
            'jav',
            'enable_notification',
            true
        );
        $onejav = Onejav::factory()->create();

        $this->assertInstanceOf(Movie::class, $onejav->movie);
        $this->assertEquals($onejav->dvd_id, $onejav->movie->dvd_id);
        $this->assertEquals($onejav->genres, $onejav->movie->genres->pluck('name')->toArray());
        $this->assertEquals($onejav->performers, $onejav->movie->performers->pluck('name')->toArray());
        $this->assertTrue($onejav->movie->isDownloadable());

        $this->assertDatabaseHas('wordpress_posts', [
            'title' => $onejav->dvd_id,
            'state_code' => State::STATE_INIT,
        ]);

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            MovieCreatedNotification::class,
            function ($notification) use ($onejav) {
                return $notification->movie->is($onejav->movie);
            }
        );

        $this->assertNull($onejav->getName());
    }

    public function testModelRefetch()
    {
        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021.html'));
        app()->instance(XCrawlerClient::class, $this->mocker);
        $onejav = Onejav::factory()->create();
        $onejav->refetch();

        $this->assertEquals('WAAA-088', $onejav->dvd_id);
    }

    public function testFindByDvdId()
    {
        $onejav = Onejav::factory()->create();
        $this->assertTrue($onejav->is(Onejav::findByDvdId($onejav->dvd_id)));
    }
}
