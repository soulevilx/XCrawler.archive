<?php

namespace App\Core\Services;

use App\Core\Models\Application;
use Illuminate\Support\Collection;

class ApplicationService
{
    private Application|Collection $models;

    public function __construct()
    {
        $this->refresh();
    }

    public function save(string $name, string $key, $value)
    {
        if (!$this->models->has($name)) {
            $model = Application::firstOrCreate([
                'name' => $name,
            ], [
                'settings' => [],
            ]);
        }

        $model = $model ?? $this->models->get($name);

        $settings = $model->settings;
        $settings[$key] = $value;
        $model->update(['settings' => $settings]);
        $this->models->put($name, $model);

        return $this;
    }

    public function remove(string $name, string $key)
    {
        $model = $this->models->get($name);
        $settings = $model->settings;
        unset($settings[$key]);
        $model->update(['settings' => $settings]);

        return $this;
    }

    public function get(string $name, string $key, $default = null)
    {
        $model = $this->models->get($name);
        $settings = $model->settings ?? [];

        return $settings[$key] ?? $default;
    }

    public static function getConfig(string $name, string $key, $default = null)
    {
        return app(ApplicationService::class)->get($name, $key, $default);
    }

    public static function setConfig(string $name, string $key, $value)
    {
        return app(ApplicationService::class)->save($name, $key, $value);
    }

    public function refresh()
    {
        $this->models = Application::all()->keyBy('name');
    }
}
