<?php

namespace App\Jav\Tests;

use Mockery\MockInterface;
use Tests\TestCase;

class JavTestCase extends TestCase
{
    protected MockInterface $xcrawlerMocker;

    public function setUp(): void
    {
        parent::setUp();
        $this->fixtures = __DIR__.'/Fixtures';

        $this->xcrawlerMocker = $this->getClientMock();
    }
}
