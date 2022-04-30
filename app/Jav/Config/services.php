<?php

return [
    'jav' => [
        'jobs_per_second' => env('XCITY_JOBS_PER_SECOND'),
        'release_jobs_after_minutes' => env('XCITY_RELEASE_JOB_AFTER_MINUTES'),
    ],
    'r18' => [
        'jobs_per_second' => env('R18_JOBS_PER_SECOND'),
        'release_jobs_after_minutes' => env('R18_RELEASE_JOB_AFTER_MINUTES'),
    ],
];
