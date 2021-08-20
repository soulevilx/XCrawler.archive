<?php

namespace App\Core\Events;

use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;

class ClientRequested
{
    public function __construct(public ResponseInterface $response)
    {
    }
}
