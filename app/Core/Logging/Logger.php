<?php

namespace App\Core\Logging;

use Monolog\Logger as MonoLogger;

class Logger
{
    public function __invoke(): MonoLogger
    {
        $logger = new MonoLogger('DatabaseHandler');
        return $logger->pushHandler(new Handler());
    }
}
