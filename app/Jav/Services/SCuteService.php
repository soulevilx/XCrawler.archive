<?php

namespace App\Jav\Services;

use App\Jav\Crawlers\SCuteCrawler;

class SCuteService
{
    public function __construct(protected SCuteCrawler $crawler)
    {
    }
}
