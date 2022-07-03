<?php

namespace App\Jav\Tests;

use Tests\TestCase;

class JavTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->fixtures = __DIR__.'/Fixtures';

        $this->xcrawlerMocker = $this->getXCrawlerClientMocker();

        foreach (class_uses($this) as $class) {
            if (method_exists($class, 'boot')) {
                $this->boot();
            }
        }

        $this->mockingXCrawler($this->mocks);
    }
}
