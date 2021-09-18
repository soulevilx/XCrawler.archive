<?php

namespace App\Core\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use RuntimeException;
use Throwable;

class BaseServiceProvider extends ServiceProvider
{
    protected array $migrations = [
    ];

    protected array $configs = [
    ];

    public function boot()
    {
        $this->testCacheConnection();
        $this->loadMigrations();
        $this->loadConfigs();
    }

    /**
     * @return $this
     */
    protected function loadMigrations()
    {
        foreach ($this->migrations as $dir) {
            if (!file_exists($dir)) {
                continue;
            }

            $this->loadMigrationsFrom($dir);
        }

        return $this;
    }

    /**
     * Load config file.
     *
     * @return $this
     */
    protected function loadConfigs()
    {
        foreach ($this->configs as $dir => $configs) {
            foreach ($configs as $config) {
                $configFile = $dir.'/'.$config.'.php';
                if (!file_exists($configFile)) {
                    continue;
                }
                $this->mergeConfigFrom($configFile, $config);
            }
        }

        return $this;
    }

    /**
     * It is possible to get a redis connection failure and it could bring the whole app down so we'll try fallback here.
     */
    private function testCacheConnection(): void
    {
        try {
            if ('redis' === config('cache.default')) {
                Cache::get('dummy');
            }
        } catch (Throwable $e) {
            report(new RuntimeException('Redis cache connection error, falling back to in-memory driver', 0, $e));
            config(['cache.default' => 'array']);
        }
    }

    /**
     * Load Routes
     *
     * @param string $file
     *
     * @return $this
     */
    protected function loadRoutes(string $file)
    {
        if (file_exists($file)) {
            $this->loadRoutesFrom($file);
        }

        return $this;
    }
}
