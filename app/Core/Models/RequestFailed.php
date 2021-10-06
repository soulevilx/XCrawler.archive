<?php

namespace App\Core\Models;

use App\Core\Models\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestFailed extends Model
{
    use HasFactory;

    protected $table = 'request_fails';

    protected $fillable = [
        'service',
        'endpoint',
        'path',
        'params',
        'message',
    ];

    protected $casts = [
        'service' => 'string',
        'endpoint' => 'string',
        'path' => 'string',
        'params' => 'array',
        'message' => 'string'
    ];
}
