<?php

return [
    'test' => [
        'base_url' => 'https://fake.com',
    ],
    'slack' => [
        'notifications' => env('SLACK_NOTIFICATIONS'),
    ],
    'telescope' => [
        'enable_all' => env('TELESCOPE_ENABLE_ALL', false)
    ]
];
