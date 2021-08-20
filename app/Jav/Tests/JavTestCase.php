<?php

namespace App\Jav\Tests;

use App\Core\Client;
use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class JavTestCase extends TestCase
{
    protected MockInterface|LegacyMockInterface $mocker;

    public function setUp(): void
    {
        parent::setUp();
        $this->fixtures = __DIR__.'/Fixtures';

        app()->bind(ResponseInterface::class, DomResponse::class);
        $this->mocker = \Mockery::mock(XCrawlerClient::class);
        $this->mocker->shouldReceive('setService')->andReturnSelf();
        $this->mocker->shouldReceive('init')->andReturnSelf();
        $this->mocker->shouldReceive('setHeaders')->andReturnSelf();
        $this->mocker->shouldReceive('setContentType')->andReturnSelf();
    }
}
