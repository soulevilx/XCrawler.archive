<?php

return [
    'flickr' => [
        'api_key' => env('FLICKR_API_KEY'),
        'secret_key'=> env('FLICKR_SECRET_KEY'),
        'storage_server' => env('FLICKR_STORAGE_SERVER'),
        'storage_server_port' => env('FLICKR_STORAGE_SERVER_PORT', 22),
        'storage_server_username' => env('FLICKR_STORAGE_SERVER_USERNAME'),
        'storage_server_password' => env('FLICKR_STORAGE_SERVER_PASSWORD'),
    ],
];
