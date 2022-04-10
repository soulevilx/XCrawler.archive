<?php

namespace App\Core;

use App\Core\Events\Client\ClientPrepared;
use App\Core\Events\Client\ClientRequested;
use App\Core\Events\Client\ClientRequestFailed;
use App\Core\Services\Facades\Application;
use Carbon\CarbonImmutable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Jooservices\XcrawlerClient\Factory;
use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;
use Jooservices\XcrawlerClient\Settings\RequestOptions;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\LaravelCacheStorage;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class XCrawlerClient
{
    private RequestOptions $requestOptions;
    private Factory $factory;
    private Logger $logger;
    private Client $client;
    private string $service;
    private array $headers;
    private string $contentType;
    private ResponseInterface $response;

    public function __construct(string $service, ResponseInterface $response)
    {
        $this->service = $service;
        $this->logger = (new Logger($service))
            ->pushHandler(
                new StreamHandler(storage_path('logs/' . $service . '/' . CarbonImmutable::now()->format('Y-m-d') . '.log'))
            );

        $this->requestOptions = new RequestOptions([
            'base_uri' => Application::getSetting($service, 'base_url'),
        ]);

        $this->response = $response;

        $this->factory = new Factory($this->logger);
        $this->factory
            ->enableRetries(3, 1, 500)
            ->addOptions($this->requestOptions->toArray())
            ->enableLogging()
            ->enableCache(new CacheMiddleware(
                new PrivateCacheStrategy(
                    new LaravelCacheStorage(
                        Cache::store('redis')
                    )
                )
            ));

        $this->client = $this->factory->make();
    }

    /**
     * Get the Response
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Set the headers
     *
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers ?? [], $headers);

        return $this;
    }

    /**
     * Set Client options
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->requestOptions->fromIterable($options);

        return $this;
    }

    /**
     * Set the content type
     *
     * @param string $contentType
     *
     * @return $this
     */
    public function setContentType(string $contentType = 'json'): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * GET Request
     *
     * @param string $endpoint
     * @param array $payload
     * @return ResponseInterface
     */
    public function get(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload);
    }

    /**
     * POST Request
     *
     * @param string $endpoint
     * @param array $payload
     * @return ResponseInterface
     */
    public function post(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'POST');
    }

    /**
     * PUT Request
     *
     * @param string $endpoint
     * @param array $payload
     * @return ResponseInterface
     */
    public function put(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'PUT');
    }

    /**
     * PATCH Request
     *
     * @param string $endpoint
     * @param array $payload
     * @return ResponseInterface
     */
    public function patch(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'PATCH');
    }

    /**
     * DELETE Request
     *
     * @param string $endpoint
     * @param array $payload
     * @return ResponseInterface
     */
    public function delete(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'DELETE');
    }

    /**
     * Perform the request
     *
     * @param string $endpoint
     * @param array $payload
     * @param string $method
     * @return ResponseInterface
     */
    protected function request(string $endpoint, array $payload = [], string $method = 'GET')
    {
        /**
         * Request options
         */
        $options = array_merge($this->requestOptions->toArray(), ['headers' => $this->headers ?? []]);

        if (isset($this->headers['auth'])) {
            $options['auth'] = $this->headers['auth'];
        }

        array_walk_recursive($payload, function (&$item) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });

        if ($method == 'GET') {
            $options['query'] = $payload;
        } else {
            switch ($this->contentType) {
                case 'application/x-www-form-urlencoded':
                    $options['form_params'] = $payload;
                    break;
                default:
                case 'json':
                    $options['json'] = $payload;
                    break;
            }
        }

        $this->response->reset();
        $this->response->endpoint = $endpoint;
        $this->response->request = $payload;

        try {
            Event::dispatch(new ClientPrepared(
                $this->service,
                $options,
                $endpoint,
                $payload,
                $method
            ));
            $response = $this->client->request($method, $endpoint, $options);
            $this->response->body = (string) $response->getBody();
            $this->response->headers = $response->getHeaders();
            $this->response->responseCode = $response->getStatusCode();
            $this->response->loadData();
        } catch (\Exception $e) {
            Event::dispatch(new ClientRequestFailed(
                $this->service,
                $options,
                $endpoint,
                $payload,
                $method,
                $e
            ));
            $this->response->responseSuccess = false;
            $this->response->responseCode = $e->getCode();
            $this->response->responseMessage = $e->getMessage();
            $this->response->body = $e->getResponse()->getBody()->getContents();
        } finally {
            Event::dispatch(new ClientRequested(
                $this->service,
                $options,
                $endpoint,
                $payload,
                $method,
                $this->response
            ));
            return $this->response;
        }
    }
}
