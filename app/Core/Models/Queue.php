<?php

namespace App\Core\Models;

class Queue extends BaseMongo
{
    protected $collection = 'queues';

    protected $fillable = [
        'payload',
    ];
}
