<?php

namespace App\Core\Events;

use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;

class ClientRequested
{
    public function __construct(
        public string            $service,
        public string            $endpoint,
        public array             $payload,
        public ResponseInterface $response)
    {
    }
}
