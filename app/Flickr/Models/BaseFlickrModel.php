<?php

namespace App\Flickr\Models;

use App\Core\Models\BaseModel;

class BaseFlickrModel extends BaseModel
{
    protected $connection = 'flickr';
}
