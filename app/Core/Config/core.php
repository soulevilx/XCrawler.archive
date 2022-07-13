<?php

return [
    'pagination' => [
        'per_page' => 15
    ],
    'sftp' => [
        'host' => env('SFTP_HOST'),
        'root_path' => env('SFTP_ROOT_PATH'),
        'username' => env('SFTP_USERNAME'),
        'password' => env('SFTP_PASSWORD'),
        'port' => env('SFTP_PORT'),
    ]
];
