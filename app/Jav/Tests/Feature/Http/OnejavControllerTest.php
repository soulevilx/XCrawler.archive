<?php

namespace App\Jav\Tests\Feature\Http;

use App\Jav\Models\Onejav;
use App\Jav\Tests\JavTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

class OnejavControllerTest extends JavTestCase
{
    public function testDownloadSucceed()
    {
        $onejav = Onejav::factory()->create();
        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021.html'));
        app()->instance(XCrawlerClient::class, $this->mocker);

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
        $this->mocker = $this->getClientMock();
        $this->mocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/july_22_2021.html'));
        app()->instance(XCrawlerClient::class, $this->mocker);

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
