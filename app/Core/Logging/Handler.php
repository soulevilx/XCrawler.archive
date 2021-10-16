<?php

namespace App\Core\Logging;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class Handler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $data = array(
            'message'       => $record['message'],
            'context'       => json_encode($record['context']),
            'level'         => $record['level'],
            'level_name'    => $record['level_name'],
            'channel'       => $record['channel'],
            'record_datetime' => $record['datetime']->format('Y-m-d H:i:s'),
            'extra'         => json_encode($record['extra']),
            'formatted'     => $record['formatted'],
            'remote_addr'   => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'user_agent'    => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => Carbon::now()
        );

        DB::table('logs')->insert($data);
    }
}
