<?php

namespace App\Core\Tests\Unit\Client;

use App\Core\Events\Client\ClientPrepared;
use App\Core\Events\Client\ClientRequested;
use App\Core\Events\Client\ClientRequestFailed;
use Exception;
use Illuminate\Support\Facades\Event;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Tests\TestCase;

class XCrawlerClientTest extends TestCase
{
    public function testRequest()
    {
        Event::fake([
            ClientPrepared::class,
            ClientRequested::class,
        ]);
        $client = $this->getXCrawlerClient(new DomResponse());
        $client->post($this->faker->url);

        Event::assertDispatched(ClientPrepared::class);
        Event::assertDispatched(ClientRequested::class);
    }

    public function testRequestFailed()
    {
        Event::fake([
            ClientPrepared::class,
            ClientRequestFailed::class,
            ClientRequested::class,
        ]);
        $client = $this->getXCrawlerClient(new Exception());
        $client->post($this->faker->url);

        Event::assertDispatched(ClientPrepared::class);
        Event::assertDispatched(ClientRequestFailed::class);
        Event::assertDispatched(ClientRequested::class);
    }
}
