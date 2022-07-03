<?php

namespace Tests;

use App\Core\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\Traits\WithMocker;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;
    use WithFaker;
    use WithMocker;

    protected string $fixtures;
    protected bool $seed = true;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Mail::fake();

        Setting::truncate();
        $this->seed();
    }
}
