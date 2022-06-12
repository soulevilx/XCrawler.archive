<?php

namespace App\Core\Tests\Unit\Services;

use App\Core\Models\Setting;
use App\Core\Services\Facades\Application;
use Tests\TestCase;

class ApplicationServiceTest extends TestCase
{
    public function testSettings()
    {
        extract($this->createSetting());

        $this->assertEquals($value, Application::getSetting($name, $key));
    }

    public function testGetSettingViaStaticMethod()
    {
        extract($this->createSetting());

        $this->assertEquals($value, Application::getSetting($name, $key));
        $default = $this->faker->name;
        $this->assertEquals(
            $default,
            Application::getSetting($this->faker->name, $key, $default)
        );
    }

    public function testInc()
    {
        $name = $this->faker->word;
        $key = $this->faker->word;
        Setting::create([
            'group' => $name,
            'field' => $key,
            'value' => 1,
        ]);

        $this->assertEquals(2, Application::inc($name, $key));
    }

    public function testGetBool()
    {
        $name = $this->faker->word;
        $key = $this->faker->word;
        Setting::create([
            'group' => $name,
            'field' => $key,
            'value' => 10,
        ]);

        $this->assertTrue(Application::getBool($name, $key));
    }

    public function testGettings()
    {
        Setting::truncate();
        extract($this->createSetting());

        $settings = Application::getSettings();
        $this->assertArrayHasKey($name, $settings);
    }

    public function testSettingWithDefault()
    {
        $value = Application::getSetting('onejav', 'test', $this->faker->url);
        $this->assertTrue(Setting::where([
            'group' => 'onejav',
            'field' => 'test',
            'value' => $value,
        ])->exists());

        $this->assertEquals($value, Application::getSetting('onejav', 'test'));
    }

    private function createSetting()
    {
        $name = $this->faker->word;
        $key = $this->faker->word;
        $value = $this->faker->numerify;
        Setting::create([
            'group' => $name,
            'field' => $key,
            'value' => $value,
        ]);

        return ['name' => $name, 'key' => $key, 'value' => $value];
    }
}
