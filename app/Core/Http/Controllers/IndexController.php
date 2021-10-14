<?php

namespace App\Core\Http\Controllers;

use App\Core\Models\State;
use App\Jav\Models\Onejav;
use App\Jav\Models\R18;
use App\Jav\Models\XCityIdol;
use App\Jav\Models\XCityVideo;
use Carbon\Carbon;

class IndexController extends BaseController
{
    public function index()
    {
        $data = [
            'Onejav' => [
                'total' => Onejav::count(),
                'today' => Onejav::where('created_at', '>=', Carbon::now()->startOfDay())->count(),
                'init' => 0,
            ],
            'R18' => [
                'total' => R18::count(),
                'today' => R18::where('created_at', '>=', Carbon::now()->startOfDay())->count(),
                'init' => R18::byState(State::STATE_INIT)->count(),
            ],
            'XCity Idols' => [
                'total' => XCityIdol::count(),
                'today' => XCityIdol::where('created_at', '>=', Carbon::now()->startOfDay())->count(),
                'init' => XCityIdol::byState(State::STATE_INIT)->count(),
            ],
            'XCity Videos' => [
                'total' => XCityVideo::count(),
                'today' => XCityVideo::where('created_at', '>=', Carbon::now()->startOfDay())->count(),
                'init' => XCityVideo::byState(State::STATE_INIT)->count(),
            ],
        ];

        $data['Onejav']['percent'] = $this->calculatePercent($data['Onejav']['total'], $data['Onejav']['today']);
        $data['R18']['percent'] = $this->calculatePercent($data['R18']['total'], $data['R18']['today']);
        $data['XCity Idols']['percent'] = $this->calculatePercent($data['XCity Idols']['total'], $data['XCity Idols']['today']);
        $data['XCity Videos']['percent'] = $this->calculatePercent($data['XCity Videos']['total'], $data['XCity Videos']['today']);

        return response()->view('welcome', ['data' => $data]);
    }

    private function calculatePercent(int $total, int $value)
    {
        return $value === 0 ? 0 : round($value * 100 / $total, 4);
    }
}
