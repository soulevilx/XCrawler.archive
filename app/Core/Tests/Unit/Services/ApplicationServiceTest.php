<?php

namespace App\Core\Tests\Unit\Services;

use App\Core\Models\Application;
use App\Core\Services\ApplicationService;
use Tests\TestCase;


class ApplicationServiceTest extends TestCase
{
    public function testSettings()
    {
        $name = $this->faker->word;
        $key = $this->faker->word;
        $value = $this->faker->numerify;
        Application::create([
            'name' => $name,
            'settings' => [
                $key => $value,
            ],
        ]);

        $service = app(ApplicationService::class);

        $this->assertEquals($value, $service->get($name, $key));
    }

    public function testGetSettingViaStaticMethod()
    {
        $name = $this->faker->word;
        $key = $this->faker->word;
        $value = $this->faker->numerify;
        Application::create([
            'name' => $name,
            'settings' => [
                $key => $value,
            ],
        ]);

        $this->assertEquals($value, ApplicationService::getConfig($name, $key));
        $default = $this->faker->name;
        $this->assertEquals($default, ApplicationService::getConfig($this->faker->name, $key, $default));
    }
}
