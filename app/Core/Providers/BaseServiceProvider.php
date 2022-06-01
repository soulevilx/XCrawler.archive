<?php

namespace App\Core\Providers;

use App\Core\Exceptions\DatabaseError;
use App\Core\Exceptions\NetworkError;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use RuntimeException;
use Throwable;

class BaseServiceProvider extends ServiceProvider
{
    protected array $migrations = [];

    protected array $configs = [];

    protected array $routes = [];

    public function boot()
    {
        $this->testCacheConnection();
        $this->testNetwork();
        foreach (['mysql', 'mongodb', 'redis'] as $connection) {
            $this->testDatabase($connection);
        }
        $this->loadMigrations();
        $this->loadConfigs();

        foreach ($this->routes as $route) {
            $this->loadRoutes($route);
        }
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

    private function testNetwork(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        try {
            if ($connected = fsockopen("www.example.com", 80)) {
                fclose($connected);
            }
        } catch (Exception) {
            throw new NetworkError();
        }
    }

    private function testDatabase(string $connection = 'mysql')
    {
        if (app()->environment('testing')) {
            return;
        }

        try {
            switch ($connection) {
                case 'redis':
                    break;
                default:
                    DB::connection($connection)->getPdo();
                    break;
            }
        } catch (Exception $exception) {
            throw new DatabaseError ($exception->getMessage());
        }
    }

    /**
     * Load Routes
     *
     * @param  string  $file
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
