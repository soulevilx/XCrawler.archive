<?php

namespace App\Jav\Tests\Unit\Models;

use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use App\Jav\Notifications\MovieCreatedNotification;
use App\Jav\Services\OnejavService;
use App\Jav\Tests\JavTestCase;
use Illuminate\Support\Facades\Notification;
use Jooservices\XcrawlerClient\Response\DomResponse;

class OnejavModelTest extends JavTestCase
{
    public function testModel()
    {
        $onejav = Onejav::factory()->create();

        $this->assertInstanceOf(Movie::class, $onejav->movie);
        $this->assertEquals($onejav->dvd_id, $onejav->movie->dvd_id);
        $this->assertEquals($onejav->genres, $onejav->movie->genres->pluck('name')->toArray());
        $this->assertEquals($onejav->performers, $onejav->movie->performers->pluck('name')->toArray());
        $this->assertTrue($onejav->movie->isDownloadable());

        Notification::assertSentTo(
            $onejav->movie,
            MovieCreatedNotification::class,
            function ($notification) use ($onejav) {
                return $notification->movie->is($onejav->movie);
            }
        );

        $this->assertNull($onejav->getName());
    }

    /**
     * @todo Move this test to Service
     * @return void
     */
    public function testModelRefetch()
    {
        $this->xcrawlerMocker = $this->getClientMock();
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_1.html'));
        app()->instance(OnejavCrawler::class, new OnejavCrawler($this->xcrawlerMocker));
        $onejav = Onejav::factory()->create();
        $onejav = app(OnejavService::class)->refetch($onejav);

        $this->assertEquals('WAAA-088', $onejav->dvd_id);
    }

    public function testFindByDvdId()
    {
        $onejav = Onejav::factory()->create();
        $this->assertTrue($onejav->is(Onejav::findByDvdId($onejav->dvd_id)));
    }
}
