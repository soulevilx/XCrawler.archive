<?php

namespace App\Core;

use App\Core\Events\Client\ClientRequested;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;
use Jooservices\XcrawlerClient\Settings\RequestOptions;
use Jooservices\XcrawlerClient\XCrawlerClient;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\LaravelCacheStorage;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Client
{
    private string $service;

    public function __construct(protected XCrawlerClient $client)
    {
    }

    public function init(string $service, ResponseInterface $response)
    {
        $this->service = $service;
        $this->client->init(
            $response,
            [
                'maxRetries' => 3,
                'delayInSec' => 1,
                'minErrorCode' => 500,
                'logger' => [
                    'instance' => (new Logger($service))
                        ->pushHandler(
                            new StreamHandler(storage_path('logs/'.$service.'/'.CarbonImmutable::now()->format('Y-m-d').'.log'))
                        ),
                    'formatter' => null,
                ],
                'caching' => [
                    'instance' => new CacheMiddleware(
                        new PrivateCacheStrategy(
                            new LaravelCacheStorage(
                                Cache::store('redis')
                            )
                        )
                    ),
                ],
            ],
            new RequestOptions([
                'base_uri' => config('services'.'.'.$service.'.base_url'),
            ])
        );

        return $this;
    }

    /**
     * GET Request.
     */
    public function get(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload);
    }

    /**
     * POST Request.
     */
    public function post(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'POST');
    }

    /**
     * PUT Request.
     */
    public function put(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'PUT');
    }

    /**
     * PATCH Request.
     */
    public function patch(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'PATCH');
    }

    /**
     * DELETE Request.
     */
    public function delete(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'DELETE');
    }

    public function request(string $endpoint, array $payload = [], string $method = 'GET')
    {
        return $this->client->{strtolower($method)}($endpoint, $payload);
    }
}
