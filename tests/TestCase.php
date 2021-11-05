<?php

namespace Tests;

use App\Core\Models\ClientRequest;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;
use Jooservices\XcrawlerClient\XCrawlerClient;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;
    use WithFaker;

    protected string $fixtures;
    protected bool $seed = true;

    protected $connectionsToTransact = ['mysql', 'flickr'];

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Mail::fake();

        ClientRequest::truncate();
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
        $response->endpoint = $this->faker->slug;
        $response->responseSuccess = true;
        $response->body = $this->getFixture($path) ?? '';
        $response->loadData();

        return $response;
    }

    /**
     * Get Successful Mocked External Service Response.
     */
    protected function getErrorMockedResponse(ResponseInterface $response, ?string $path = null): ResponseInterface
    {
        $response->endpoint = $this->faker->slug;
        $response->responseSuccess = false;
        $response->body = $this->getFixture($path) ?? '';
        $response->loadData();

        return $response;
    }

    protected function refreshTestDatabase()
    {
        if (!RefreshDatabaseState::$migrated) {
            $this->artisan('migrate:fresh', $this->migrateFreshUsing());

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}
