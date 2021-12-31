<?php

namespace App\Jav\Events;

use App\Jav\Models\Onejav;

class OnejavDownloadCompleted
{
    public function __construct(public Onejav $onejav)
    {
    }
}
