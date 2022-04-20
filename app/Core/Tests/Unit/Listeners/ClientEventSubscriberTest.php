<?php

namespace App\Core\Tests\Unit\Listeners;

use App\Core\Models\BaseMongo;
use App\Core\XCrawlerClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Jooservices\XcrawlerClient\Factory;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Tests\TestCase;

class ClientEventSubscriberTest extends TestCase
{
    public function testRequest()
    {
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

        $url = $this->faker->url;
        $client->post($url);

        $this->assertDatabaseHas('client_requests', [
            'service' => 'test',
            'base_uri' => null,
            'endpoint' => 'fake',
            'method' => 'POST',
            'is_succeed' => true,
        ], BaseMongo::CONNECTION_NAME);
    }

    public function testRequestFailed()
    {
        $mocker = \Mockery::mock(Factory::class);
        $mocker->shouldReceive('enableRetries')->andReturnSelf();
        $mocker->shouldReceive('addOptions')->andReturnSelf();
        $mocker->shouldReceive('enableLogging')->andReturnSelf();
        $mocker->shouldReceive('enableCache')->andReturnSelf();

        $clientMocker = \Mockery::mock(Client::class);
        $clientMocker
            ->shouldReceive('request')
            ->andThrow(new \Exception());

        $mocker->shouldReceive('make')->andReturn($clientMocker);
        app()->instance(Factory::class, $mocker);
        $client = new XCrawlerClient('test', new DomResponse());

        $url = $this->faker->url;
        $client->post($url);

        $this->assertDatabaseHas('client_requests', [
            'service' => 'test',
            'base_uri' => null,
            'endpoint' => $url,
            'method' => 'POST',
            'is_succeed' => false,
        ], BaseMongo::CONNECTION_NAME);
    }
}
