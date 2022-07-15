<?php

namespace App\Jav\Tests\Feature\Console;

use App\Jav\Jobs\R18\DailyFetch;
use App\Jav\Jobs\R18\ItemFetch;
use App\Jav\Jobs\R18\ReleaseFetch;
use App\Jav\Models\R18;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\R18Mocker;
use Illuminate\Support\Facades\Queue;

class R18Test extends JavTestCase
{
    use R18Mocker;

    public function setUp(): void
    {
        parent::setUp();

        Queue::fake();
    }

    public function testR18InvalidTask()
    {
        $this->artisan('jav:r18 fake');
        Queue::assertNothingPushed();
    }

    public function testR18Release()
    {
        $this->artisan('jav:r18 release');
        Queue::assertPushed(function (ReleaseFetch $job) {
            return 'crawling' === $job->queue;
        });
    }

    public function testR1Daily()
    {
        $this->artisan('jav:r18 daily');
        Queue::assertPushed(function (DailyFetch $job) {
            return 'crawling' === $job->queue;
        });
    }

    public function testR18ItemNoItem()
    {
        $this->artisan('jav:r18 item');
        Queue::assertNotPushed(function (ItemFetch $job) {
            return 'crawling' === $job->queue;
        });
    }

    public function testR18Item()
    {
        $r18 = R18::factory()->create();
        $this->artisan('jav:r18 item');
        Queue::assertPushed(function (ItemFetch $job) use ($r18) {
            return 'crawling' === $job->queue && $r18->is($job->model) && $job->model->isProcessingState();
        });
    }

    public function testR18ItemWithNoItems()
    {
        $this->artisan('jav:r18 item --limit=5');
    }

    public function testR18SpecificItem()
    {
        $r18 = R18::factory()->create();
        $this->artisan('jav:r18 item --id=' . $r18->id);
        Queue::assertPushed(function (ItemFetch $job) use ($r18) {
            return 'crawling' === $job->queue && $r18->is($job->model) && $job->model->isProcessingState();
        });
    }
}
