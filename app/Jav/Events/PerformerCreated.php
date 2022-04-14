<?php

namespace App\Jav\Events;

use App\Jav\Models\Performer;

class PerformerCreated
{
    public function __construct(protected Performer $genre)
    {
    }
}
