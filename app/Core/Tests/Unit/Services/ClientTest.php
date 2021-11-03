<?php

namespace App\Core\Tests\Unit\Services;

use App\Core\Client;
use App\Core\Notifications\ClientRequestFailedNotification;
use App\Core\Services\ApplicationService;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;
use Tests\TestCase;

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
        $this->mocker->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(new DomResponse()));
        app()->instance(XCrawlerClient::class, $this->mocker);
        $client = app(Client::class)->init('test', new DomResponse());
        $client->request($this->faker->slug);

        $this->assertDatabaseHas('client_requests', [
            'service' => 'test',
            'base_uri' => 'https://fake.com',
            'is_succeed' => true,
        ], 'mongodb');
    }

    public function testClientServiceRequestFailed()
    {
        $this->mocker->shouldReceive('get')
            ->andReturn($this->getErrorMockedResponse(new DomResponse()));
        app()->instance(XCrawlerClient::class, $this->mocker);

        $client = app(Client::class)->init('test', new DomResponse());
        ApplicationService::setConfig('core', 'enable_slack_notification', true);
        $client->request($this->faker->slug);

        $this->assertDatabaseHas('client_requests', [
            'service' => 'test',
            'base_uri' => 'https://fake.com',
            'is_succeed' => false,
        ], 'mongodb');

        Notification::assertSentTo(new AnonymousNotifiable(), ClientRequestFailedNotification::class);
    }

    public function testClientServiceRequestFailedTriggerNotification()
    {
        $this->mocker->shouldReceive('get')
            ->andReturn($this->getErrorMockedResponse(new DomResponse()));
        app()->instance(XCrawlerClient::class, $this->mocker);
        $client = app(Client::class)->init('test', new DomResponse());
        ApplicationService::setConfig('core', 'enable_slack_notification', true);
        $client->request($this->faker->slug);

        $this->assertDatabaseHas('client_requests', [
            'service' => 'test',
            'base_uri' => 'https://fake.com',
            'is_succeed' => false,
        ], 'mongodb');

        Notification::assertSentTo(new AnonymousNotifiable(), ClientRequestFailedNotification::class);
    }
}
