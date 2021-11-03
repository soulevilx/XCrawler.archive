<?php

namespace App\Core\Models;

class ClientRequest extends BaseMongo
{
    protected $collection = 'client_requests';

    protected $fillable = [
        'service',
        'base_uri',
        'endpoint',
        'payload',
        'body',
        'messages',
        'code',
        'is_succeed',
    ];

    protected $casts = [
        'service' => 'string',
        'base_uri' => 'string',
        'endpoint' => 'string',
        'payload' => 'array',
        'body' => 'string',
        'messages' => 'string',
        'code' => 'integer',
        'is_succeed' => 'boolean',
    ];
}
