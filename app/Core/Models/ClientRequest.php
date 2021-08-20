<?php

namespace App\Core\Models;

use App\Core\Client;
use App\Core\Models\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jooservices\XcrawlerClient\Response\DomResponse;

class ClientRequest extends Model
{
    use HasFactory;


    protected $fillable = [
        'service',
        'base_uri',
        'endpoint',
        'payload',
        'body',
        'is_succeed',
    ];

    protected $casts = [
        'service' => 'string',
        'base_uri' => 'string',
        'endpoint' => 'string',
        'payload' => 'array',
        'body' => 'string',
        'is_succeed' => 'boolean',
    ];

    protected $table = 'client_requests';
}
