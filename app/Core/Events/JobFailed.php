<?php

namespace App\Core\Events;

class JobFailed
{
    public function __construct(protected $job)
    {
    }
}
