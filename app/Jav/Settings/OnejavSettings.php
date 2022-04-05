<?php

namespace App\Jav\Settings;

use Spatie\LaravelSettings\Settings;

class OnejavSettings extends Settings
{
    public int $total_pages;

    public static function group(): string
    {
        return 'onejav';
    }
}
