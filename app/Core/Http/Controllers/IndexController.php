<?php

namespace App\Core\Http\Controllers;

use App\Jav\Models\Onejav;
use App\Jav\Models\R18;
use App\Jav\Models\XCityIdol;
use App\Jav\Models\XCityVideo;
use Carbon\Carbon;

class IndexController extends BaseController
{
    public function index()
    {
        $total['onejav'] = Onejav::count();
        $total['r18'] = R18::count();
        $total['xcityIdols'] = XCityIdol::count();
        $total['xcityVideos'] = XCityVideo::count();

        $today['onejav'] = Onejav::where('created_at', '>=', Carbon::now()->startOfDay())->count();
        $today['r18'] = R18::where('created_at', '>=', Carbon::now()->startOfDay())->count();
        $today['xcityIdols'] = XCityIdol::where('created_at', '>=', Carbon::now()->startOfDay())->count();
        $today['xcityVideos'] = XCityVideo::where('created_at', '>=', Carbon::now()->startOfDay())->count();

        $inc['onejav'] = $this->calculatePercent($total['onejav'], $today['onejav']);
        $inc['r18'] = $this->calculatePercent($total['r18'], $today['r18']);
        $inc['xcityIdols'] = $this->calculatePercent($total['xcityIdols'], $today['xcityIdols']);
        $inc['xcityVideos'] = $this->calculatePercent($total['xcityVideos'], $today['xcityVideos']);

        return response()->view('welcome', ['total' => $total, 'inc' => $inc]);
    }

    private function calculatePercent(int $total, int $value)
    {
        return $value === 0 ? 0 : round($value * 100 / $total, 4);
    }
}
