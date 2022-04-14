<?php

namespace App\Core\Events\Client;

class ClientRequestFailed
{
    public function __construct(
        public string $service,
        public array  $options,
        public string $endpoint,
        public array  $payload,
        public string $method,
        public \Exception    $exception
    )
    {
    }
}
