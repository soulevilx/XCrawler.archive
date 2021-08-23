<?php

namespace App\Jav\Tests\Unit\Models;

use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\OnejavMocker;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;
use Tests\TestCase;

class OnejavModelTest extends JavTestCase
{
    public function testModel()
    {
        $onejav = Onejav::factory()->create();

        $this->assertInstanceOf(Movie::class, $onejav->movie);
        $this->assertEquals($onejav->dvd_id, $onejav->movie->dvd_id);
        $this->assertEquals($onejav->genres, $onejav->movie->genres->pluck('name')->toArray());
        $this->assertEquals($onejav->performers, $onejav->movie->performers->pluck('name')->toArray());
        $this->assertTrue($onejav->movie->is_downloadable);
    }

    public function testModelRefetch()
    {
        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021.html'))
        ;
        app()->instance(XCrawlerClient::class, $this->mocker);
        $onejav = Onejav::factory()->create();
        $onejav->refetch();

        $this->assertEquals('WAAA-088', $onejav->dvd_id);
    }
}
