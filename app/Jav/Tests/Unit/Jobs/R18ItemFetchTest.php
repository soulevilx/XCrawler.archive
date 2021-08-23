<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Jav\Crawlers\R18Crawler;
use App\Jav\Jobs\R18\ItemFetch;
use App\Jav\Models\Movie;
use App\Jav\Models\R18;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\R18Mocker;
use Jooservices\XcrawlerClient\Response\JsonResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;

/**
 * @internal
 * @coversNothing
 */
class R18ItemFetchTest extends JavTestCase
{
    use R18Mocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->mocker
            ->shouldReceive('get')
            ->andReturn($this->getSuccessfulMockedResponse(app(JsonResponse::class), 'R18/item.json'))
        ;

        app()->instance(XCrawlerClient::class, $this->mocker);

        $this->crawler = app(R18Crawler::class);
    }

    public function testItemFetch()
    {
        $model = R18::factory()->create();
        ItemFetch::dispatch($model);

        $model->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $model->state_code);
        $this->assertEquals('RKI-506', $model->dvd_id);

        $this->assertInstanceOf(Movie::class, $model->movie);
    }
}
