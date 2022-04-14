<?php

namespace App\Core\Events\Client;

use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;

class ClientRequested
{
    public function __construct(
        public string            $service,
        public array             $options,
        public string            $endpoint,
        public array             $payload,
        public string            $method,
        public ?ResponseInterface $response
    )
    {
    }
}
