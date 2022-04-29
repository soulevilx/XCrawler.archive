<?php

namespace App\Jav\Tests\Feature\Http;

use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Models\Onejav;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\OnejavMocker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Jooservices\XcrawlerClient\Response\DomResponse;

class OnejavControllerTest extends JavTestCase
{
    use OnejavMocker;

    public function testDownloadSucceed()
    {
        $this->xcrawlerMocker = $this->getClientMock();
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021_page_1.html'));
        app()->instance(OnejavCrawler::class, new OnejavCrawler($this->xcrawlerMocker));

        $onejav = Onejav::factory()->create();
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')
            ->andReturn(new Response());
        app()->instance(Client::class, $client);
        $this->post('/jav/onejav/' . $onejav->dvd_id . '/download')->getContent();

        $this->assertEquals(1, $onejav->refresh()->downloads->count());
        $this->assertDatabaseHas('downloads', [
            'model_id' => $onejav->id,
            'model_type' => Onejav::class,
        ]);
    }

    public function testDownloadFailed()
    {
        $onejav = Onejav::factory()->create();
        $this->xcrawlerMocker = $this->getClientMock();
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021.html'));
        app()->instance(OnejavCrawler::class, new OnejavCrawler($this->xcrawlerMocker));

        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')
            ->andReturn(new Response(303));
        app()->instance(Client::class, $client);
        $this->post('/jav/onejav/' . $onejav->dvd_id . '/download')->getContent();

        $this->assertEquals(0, $onejav->refresh()->downloads->count());
        $this->assertDatabaseMissing('downloads', [
            'model_id' => $onejav->id,
            'model_type' => Onejav::class,
        ]);
    }
}
