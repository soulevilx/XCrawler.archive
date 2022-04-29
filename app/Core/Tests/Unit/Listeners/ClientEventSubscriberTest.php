<?php

namespace App\Core\Tests\Unit\Listeners;

use App\Core\Models\BaseMongo;
use Exception;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Tests\TestCase;

class ClientEventSubscriberTest extends TestCase
{
    public function testRequest()
    {
        $client = $this->getXCrawlerClient(new DomResponse());
        $url = $this->faker->url;
        $client->post($url);

        $this->assertDatabaseHas('client_requests', [
            'service' => 'test',
            'base_uri' => null,
            'endpoint' => $url,
            'method' => 'POST',
            'is_succeed' => true,
        ], BaseMongo::CONNECTION_NAME);
    }

    public function testRequestFailed()
    {
        $client = $this->getXCrawlerClient(new Exception());
        $url = $this->faker->url;
        $client->post($url);

        $this->assertDatabaseHas('client_requests', [
            'service' => 'test',
            'base_uri' => null,
            'endpoint' => $url,
            'method' => 'POST',
            'is_succeed' => false,
        ], BaseMongo::CONNECTION_NAME);
    }
}
