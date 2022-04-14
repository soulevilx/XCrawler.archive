<?php

namespace App\Jav\Services;

use App\Jav\Crawlers\SCuteCrawler;

class SCuteService
{
    public const SERVICE_NAME = 'scute';

    public function __construct(protected SCuteCrawler $crawler)
    {
    }
}
