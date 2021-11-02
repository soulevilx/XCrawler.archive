<?php

namespace App\Core\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class BaseMongo extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mongodb';

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
}
