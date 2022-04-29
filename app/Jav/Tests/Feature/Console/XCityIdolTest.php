<?php

namespace App\Jav\Tests\Feature\Console;

use App\Jav\Jobs\XCity\Idol\FetchIdolLinks;
use App\Jav\Jobs\XCity\Idol\FetchIdol;
use App\Jav\Jobs\XCity\Idol\InitIdolIndex;
use App\Jav\Jobs\XCity\Idol\UpdatePagesCount;
use App\Jav\Jobs\XCity\Idol\UpdateSubPages;
use App\Jav\Models\XCityIdol;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;
use Illuminate\Support\Facades\Queue;

class XCityIdolTest extends JavTestCase
{
    use XCityIdolMocker;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadXCityIdolMocker();

        Queue::fake();
    }

    public function testDaily()
    {
        $this->artisan('jav:xcity-idol daily');

        Queue::assertPushed(FetchIdolLinks::class);
    }

    public function testRelease()
    {
        $this->artisan('jav:xcity-idol release');

        Queue::assertPushedWithChain(InitIdolIndex::class, [
            FetchIdolLinks::class,
        ]);
    }

    public function testItem()
    {
        $model = XCityIdol::factory()->create([
            'url' => 'detail/13125',
        ]);

        $this->artisan('jav:xcity-idol item');

        Queue::assertPushed(function (FetchIdol $job) use ($model) {
            return 'crawling' === $job->queue
                && $model->is($job->model)
                && $job->model->isProcessingState();
        });
    }

    public function testSubPages()
    {
        $this->artisan('jav:xcity-idol sub-pages');

        Queue::assertPushed(UpdateSubPages::class);
    }

    public function testPagesCount()
    {
        $subPages = $this->getService()->getSubPages();

        $this->artisan('jav:xcity-idol pages-count');
        foreach ($subPages as $kana) {
            Queue::assertPushed(UpdatePagesCount::class, function (UpdatePagesCount $job) use ($kana) {
                return $job->kana === str_replace('/idol/?kana=', '', $kana);
            });
        }
    }
}
