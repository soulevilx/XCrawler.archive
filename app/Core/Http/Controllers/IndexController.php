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


        $inc['onejav'] = $total['onejav'] === 0 ? 0 : $today['onejav'] * 100 / Onejav::where('created_at', '<', Carbon::now()->endOfDay())->count();
        $inc['r18'] = $total['r18'] === 0 ? 0 : $today['r18'] * 100 / R18::where('created_at', '<', Carbon::now()->endOfDay())->count();
        $inc['xcityIdols'] = $total['xcityIdols'] === 0 ? 0 : $today['xcityIdols'] * 100 / XCityIdol::where('created_at', '<', Carbon::now()->endOfDay())->count();
        $inc['xcityVideos'] = $total['xcityVideos'] === 0 ? 0 : $today['xcityVideos'] * 100 / XCityVideo::where('created_at', '<', Carbon::now()->endOfDay())->count();

        return response()->view('welcome', ['total' => $total, 'inc' => $inc]);
    }
}
