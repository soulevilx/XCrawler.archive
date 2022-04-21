<?php

namespace App\Jav\Tests\Unit\Services;

use App\Jav\Services\SCuteService;
use Tests\TestCase;

class SCuteServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->service = app(SCuteService::class);
    }
}
