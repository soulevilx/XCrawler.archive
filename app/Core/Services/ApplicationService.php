<?php

namespace App\Core\Services;

use App\Core\Models\Setting;

class ApplicationService
{
    protected array $settings;

    public function __construct()
    {
        $this->refresh();
    }

    public function getSettings(): array
    {
        $this->refresh();

        return $this->settings;
    }

    public function getSetting(string $group, string $field, $default = null)
    {
        $this->refresh();

        if (isset($this->settings[$group]) && isset($this->settings[$group][$field])) {
            return $this->settings[$group][$field];
        }

        $this->setSetting($group, $field, $default);
        return $default;
    }

    public function setSetting(string $group, string $field, $value): ApplicationService
    {
        $this->settings[$group][$field] = $value;
        $this->save($group, $field, $value);

        return $this;
    }

    public function setSettings(array $settings): ApplicationService
    {
        $this->settings = $settings;

        foreach ($this->settings as $group => $data) {
            foreach ($data as $field => $value) {
                $this->save($group, $field, $value);
            }
        }

        return $this;
    }

    public function getInt(string $group, string $field, $default = null): int
    {
        return (int) $this->getSetting($group, $field, $default);
    }

    public function getString(string $group, string $field, $default = null): string
    {
        return (string) $this->getSetting($group, $field, $default);
    }

    public function getBool(string $group, string $field, $default = null): bool
    {
        return (bool) $this->getSetting($group, $field, $default);
    }

    public function getArray(string $group, string $field): array
    {
        $value = $this->getSetting($group, $field);

        return $value ?? [];
    }

    public function inc(string $group, string $field): int
    {
        $value = $this->getInt($group, $field);
        $value++;

        $this->setSetting($group, $field, $value);

        return $value;
    }

    protected function save(string $group, string $field, $value): ApplicationService
    {
        Setting::updateOrCreate([
            'group' => $group,
            'field' => $field,
            ], [
            'value' => $value,
        ]);

        $this->refresh();

        return $this;
    }

    public function refresh(): ApplicationService
    {
        Setting::all()->each(function ($item) {
            $this->settings[$item->group][$item->field] = $item->value;
        });

        return $this;
    }
}
