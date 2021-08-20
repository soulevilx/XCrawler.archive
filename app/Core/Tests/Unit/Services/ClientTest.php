<?php

namespace App\Core\Tests\Unit\Services;

use App\Core\Client;
use App\Core\Events\ClientRequested;
use Illuminate\Support\Facades\Event;
use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ClientTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->fixtures = __DIR__.'/../../Fixtures';

        app()->bind(ResponseInterface::class, DomResponse::class);
        $this->mocker = \Mockery::mock(XCrawlerClient::class);
        $this->mocker->shouldReceive('init')->andReturnSelf();
        $this->mocker->shouldReceive('setHeaders')->andReturnSelf();
        $this->mocker->shouldReceive('setContentType')->andReturnSelf();
    }

    public function testClientServiceRequestSucceed()
    {
        Event::fake();
        $this->mocker->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse())
        ;
        app()->instance(XCrawlerClient::class, $this->mocker);
        $client = app(Client::class)->init('test', new DomResponse());
        $client->request($this->faker->slug);

        Event::assertDispatched(ClientRequested::class);

        $this->assertDatabaseHas('client_requests', [
            'service' => 'test',
            'base_uri' => 'https://fake.com',
            'is_succeed' => true,
        ]);
    }

    public function testClientServiceRequestFailed()
    {
        Event::fake();
        $this->mocker->shouldReceive('get')
            ->andReturn($this->getErrorMockedResponse())
        ;
        app()->instance(XCrawlerClient::class, $this->mocker);
        $client = app(Client::class)->init('test', new DomResponse());
        $client->request($this->faker->slug);

        Event::assertDispatched(ClientRequested::class);

        $this->assertDatabaseHas('client_requests', [
            'service' => 'test',
            'base_uri' => 'https://fake.com',
            'is_succeed' => false,
        ]);
    }
}
