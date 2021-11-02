<?php

namespace App\Jav\Tests;

use Mockery\MockInterface;
use Tests\TestCase;

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
