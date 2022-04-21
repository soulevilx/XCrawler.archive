<?php

namespace App\Core\Logging;

use App\Core\Models\Log;
use Carbon\Carbon;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class Handler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        Log::create([
            'message'       => $record['message'],
            'context'       => json_encode($record['context']),
            'level'         => $record['level'],
            'level_name'    => $record['level_name'],
            'channel'       => $record['channel'],
            'record_datetime' => $record['datetime']->format('Y-m-d H:i:s'),
            'extra'         => json_encode($record['extra']),
            'formatted'     => $record['formatted'],
            'remote_addr'   => ip2long(request()->ip()),
            'user_agent'    => request()->server('HTTP_USER_AGENT'),
            'created_at' => Carbon::now()
        ]);
    }
}
