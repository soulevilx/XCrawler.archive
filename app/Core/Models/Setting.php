<?php

namespace App\Core\Models;

class Setting extends BaseMongo
{
    protected $collection = 'settings';

    protected $fillable = [
        'group',
        'field',
        'value',
    ];
}
