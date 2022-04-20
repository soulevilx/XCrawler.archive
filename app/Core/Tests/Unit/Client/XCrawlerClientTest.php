<?php

namespace App\Core\Tests\Unit\Client;

use App\Core\Events\Client\ClientPrepared;
use App\Core\Events\Client\ClientRequested;
use App\Core\XCrawlerClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Event;
use Jooservices\XcrawlerClient\Factory;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Tests\TestCase;

class XCrawlerClientTest extends TestCase
{
    public function testRequest()
    {
        Event::fake([
            ClientPrepared::class,
            ClientRequested::class,
        ]);
        $mocker = \Mockery::mock(Factory::class);
        $mocker->shouldReceive('enableRetries')->andReturnSelf();
        $mocker->shouldReceive('addOptions')->andReturnSelf();
        $mocker->shouldReceive('enableLogging')->andReturnSelf();
        $mocker->shouldReceive('enableCache')->andReturnSelf();

        $response = new Response();

        $clientMocker = \Mockery::mock(Client::class);
        $clientMocker
            ->shouldReceive('request')
            ->andReturn($response);

        $mocker->shouldReceive('make')->andReturn($clientMocker);
        app()->instance(Factory::class, $mocker);
        $client = new XCrawlerClient('test', new DomResponse());

        $client->post($this->faker->url);

        Event::assertDispatched(ClientPrepared::class);
        Event::assertDispatched(ClientRequested::class);
    }
}
