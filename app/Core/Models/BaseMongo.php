<?php

namespace App\Core\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class BaseMongo extends Model
{
    public const CONNECTION_NAME = 'mongodb';

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = self::CONNECTION_NAME;
}
