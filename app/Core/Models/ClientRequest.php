<?php

namespace App\Core\Models;

class ClientRequest extends BaseMongo
{
    protected $collection = 'client_requests';

    protected $guarded = [];

    protected $casts = [
        'service' => 'string',
        'base_uri' => 'string',
        'endpoint' => 'string',
        'options' => 'array',
        'payload' => 'array',
        'body' => 'string',
        'messages' => 'string',
        'code' => 'integer',
        'is_succeed' => 'boolean',
    ];
}
