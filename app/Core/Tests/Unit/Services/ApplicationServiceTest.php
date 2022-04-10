<?php

namespace App\Core\Tests\Unit\Services;

use App\Core\Models\Setting;
use App\Core\Services\Facades\Application;
use Tests\TestCase;

class ApplicationServiceTest extends TestCase
{
    public function testSettings()
    {
        $name = $this->faker->word;
        $key = $this->faker->word;
        $value = $this->faker->numerify;
        Setting::create([
            'group' => $name,
            'field' => $key,
            'value' => $value,
        ]);

        Application::refresh();
        $this->assertEquals($value, Application::getSetting($name, $key));
    }

    public function testGetSettingViaStaticMethod()
    {
        $name = $this->faker->word;
        $key = $this->faker->word;
        $value = $this->faker->numerify;
        Setting::create([
            'group' => $name,
            'field' => $key,
            'value' => $value,
        ]);

        Application::refresh();
        $this->assertEquals($value, Application::getSetting($name, $key));
        $default = $this->faker->name;
        $this->assertEquals($default, Application::getSetting($this->faker->name, $key, $default));
    }
}
