<?php

namespace Tests;

use App\Core\XCrawlerClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Jooservices\XcrawlerClient\Factory;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;
    use WithFaker;

    protected string $fixtures;
    protected bool $seed = true;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Mail::fake();

        $this->seed();
    }

    /**
     * @return MockInterface
     */
    protected function getClientMock(): MockInterface
    {
        $mocker = \Mockery::mock(XCrawlerClient::class);
        $mocker->shouldReceive('setService')->andReturnSelf();
        $mocker->shouldReceive('init')->andReturnSelf();
        $mocker->shouldReceive('setHeaders')->andReturnSelf();
        $mocker->shouldReceive('setContentType')->andReturnSelf();

        return $mocker;
    }

    protected function getXCrawlerClient($response): XCrawlerClient
    {
        $clientMocker = \Mockery::mock(Client::class);

        if ($response instanceof DomResponse) {
            $clientMocker
                ->shouldReceive('request')
                ->andReturn($response);
        } else {
            $clientMocker
                ->shouldReceive('request')
                ->andThrow($response);
        }

        $mocker = \Mockery::mock(Factory::class);
        $mocker->shouldReceive('enableRetries')->andReturnSelf();
        $mocker->shouldReceive('addOptions')->andReturnSelf();
        $mocker->shouldReceive('enableLogging')->andReturnSelf();
        $mocker->shouldReceive('enableCache')->andReturnSelf();
        $mocker->shouldReceive('make')->andReturn($clientMocker);

        app()->instance(Factory::class, $mocker);
        return new XCrawlerClient('test', new DomResponse());
    }

    protected function getFixture(?string $path): ?string
    {
        if (!$path || !file_exists($this->fixtures.'/'.$path)) {
            return null;
        }

        return file_get_contents($this->fixtures.'/'.$path);
    }

    /**
     * Get Successful Mocked External Service Response.
     */
    protected function getSuccessfulMockedResponse(ResponseInterface $response, string $path = null): ResponseInterface
    {
        $response->reset(
            200,
            [],
            $this->getFixture($path) ?? '',
        );

        return $response;
    }

    /**
     * Get Successful Mocked External Service Response.
     */
    protected function getErrorMockedResponse(
        ResponseInterface $response,
        ?string $path = null,
        ?int $responseCode = null
    ): ResponseInterface {
        $response->reset(
            $responseCode ?? 500,
            [],
            $this->getFixture($path) ?? '',
        );
        $response->isSucceed = false;

        return $response;
    }
}
