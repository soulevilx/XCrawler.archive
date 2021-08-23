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
    protected MockInterface $mocker;

    public function setUp(): void
    {
        parent::setUp();
        $this->fixtures = __DIR__.'/Fixtures';

        $this->mocker = $this->getClientMock();
    }
}
