<?php

namespace App\Core\Models;

class Log extends BaseMongo
{
    protected $collection = 'logs';

    protected $fillable = [
        'message',
        'context',
        'level',
        'level_name',
        'channel',
        'record_datetime',
        'extra',
        'formatted',
        'remote_addr',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'message' => 'string',
        'context' => 'array',
        'level' => 'string',
        'level_name' => 'string',
        'channel' => 'string',
        'record_datetime' => 'datetime:Y-m-d H:i:s',
        'extra' => 'array',
        'formatted' => 'string',
        'remote_addr' => 'integer',
        'user_agent' => 'string',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
}
