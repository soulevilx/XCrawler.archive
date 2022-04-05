<?php

namespace App\Jav\Models\Settings;

use App\Jav\Services\OnejavService;
use Spatie\LaravelSettings\Settings;

class OnejavSetting extends Settings
{

    public static function group(): string
    {
        return OnejavService::SERVICE_NAME;
    }
}
