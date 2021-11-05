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
}
