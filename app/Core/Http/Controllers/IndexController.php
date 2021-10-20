<?php

namespace App\Core\Http\Controllers;

use App\Core\Services\AnalyticsService;

class IndexController extends BaseController
{
    public function index(AnalyticsService $service)
    {
        return response()->view(
            'welcome',
            [
                'report' => $service->total()->today()->state()->report(),
                'movies' => $service->movies(),
            ]
        );
    }
}
