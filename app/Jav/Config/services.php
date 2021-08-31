<?php

use App\Jav\Models\Onejav;
use App\Jav\Models\R18;
use App\Jav\Models\XCityIdol;
use App\Jav\Models\XCityVideo;
use Carbon\Carbon;

return [
    'onejav' => [
        'base_url' => Onejav::BASE_URL,
    ],
    'r18' => [
        'base_url' => R18::BASE_URL,
    ],
    'xcity_idol' => [
        'base_url' => XCityIdol::BASE_URL,
    ],
    'xcity_video' => [
        'base_url' => XCityVideo::BASE_URL,
        'from_date' =>'20010101'
    ]
];
