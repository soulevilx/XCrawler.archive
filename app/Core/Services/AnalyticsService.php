<?php

namespace App\Core\Services;

use App\Core\Models\State;
use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use App\Jav\Models\R18;
use App\Jav\Models\XCityIdol;
use App\Jav\Models\XCityVideo;
use App\Jav\Services\OnejavService;
use App\Jav\Services\R18Service;
use App\Jav\Services\XCityIdolService;
use App\Jav\Services\XCityVideoService;
use Carbon\Carbon;

class AnalyticsService
{
    protected array $serviceMaps = [
        'onejav' => Onejav::class,
        'r18' => R18::class,
        'xcityidol' => XCityIdol::class,
        'xcityvideo' => XCityVideo::class,
    ];

    protected array $report = [];

    public const SERVICE_LABELS = [
        'onejav' => OnejavService::SERVICE_LABEL,
        'r18' => R18Service::SERVICE_LABEL,
        'xcityidol' => XCityIdolService::SERVICE_LABEL,
        'xcityvideo' => XCityVideoService::SERVICE_LABEL,
    ];

    public function total()
    {
        foreach ($this->serviceMaps as $key => $model) {
            $this->report[$key][__FUNCTION__] = number_format($model::count());
        }

        return $this;
    }

    public function today(string $by = 'created_at')
    {
        foreach ($this->serviceMaps as $key => $model) {
            $this->report[$key][__FUNCTION__] = number_format($model::where($by, '>=', Carbon::now()->startOfDay())->count());
        }

        return $this;
    }

    public function state(string $state = State::STATE_INIT)
    {
        foreach ($this->serviceMaps as $key => $model) {
            $this->report[$key][__FUNCTION__][$state] = number_format($key === 'onejav' ? 0 : $model::where(['state_code' => $state])->count());
        }

        return $this;
    }

    public function movies(int $limit = 10)
    {
        return Movie::where('created_at', '>=', Carbon::now()->startOfDay())->limit($limit)->get();
    }

    public function report(): array
    {
        return $this->report;
    }
}
