<?php

namespace App\Jav\Events\XCity;

class IdolReleaseExecuted
{
    public function __construct(public string $kana)
    {
    }
}
