<?php

use App\Jav\Models\Onejav;
use App\Jav\Models\R18;
use App\Jav\Models\XCityIdol;
use App\Jav\Models\XCityVideo;

return [
    'onejav' => [
        'base_url' => Onejav::BASE_URL,
        'total_pages' => 8500,
    ],
    'r18' => [
        'base_url' => R18::BASE_URL,
    ],
    'xcity_idol' => [
        'base_url' => XCityIdol::BASE_URL,
    ],
    'xcity_video' => [
        'base_url' => XCityVideo::BASE_URL,
        'from_date' => '20010101'
    ],
    'jav' => [
        'download_dir' => env('JAV_DOWNLOAD_DIR', storage_path('downloads')),
        'enable_notification' => env('JAV_ENABLE_NOTIFICATION', false),
        'enable_post_to_wordpress' => env('ENABLE_POST_TO_WORDPRESS', true),
        'slack_notifications' => env('JAV_SLACK_NOTIFICATIONS'),
    ]
];
