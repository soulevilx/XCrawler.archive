<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Notification;
use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;
    use WithFaker;
    use WithoutMiddleware;

    protected string $fixtures;
    protected bool $seed = true;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    protected function getFixture(string $path): string
    {
        if (!file_exists($this->fixtures.'/'.$path)) {
            return '';
        }

        return file_get_contents($this->fixtures.'/'.$path);
    }

    /**
     * Get Successful Mocked External Service Response.
     */
    protected function getSuccessfulMockedResponse(string $path): ResponseInterface
    {
        $clientResponse = app(ResponseInterface::class);
        $clientResponse->responseSuccess = true;
        $clientResponse->body = $this->getFixture($path);
        $clientResponse->loadData();

        return $clientResponse;
    }

    /**
     * Get Successful Mocked External Service Response.
     */
    protected function getErrorMockedResponse(string $path = null): ResponseInterface
    {
        $clientResponse = app(ResponseInterface::class);
        $clientResponse->responseSuccess = false;
        if ($path) {
            $clientResponse->body = $this->getFixture($path);
            $clientResponse->loadData();
        }

        return $clientResponse;
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
