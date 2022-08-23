<?php

namespace App\Core\Http\Controllers;

use App\Jav\Repositories\OnejavRespository;
use App\Jav\Repositories\R18Repository;
use App\Jav\Repositories\XCityIdolRepository;
use App\Jav\Repositories\XCityVideoRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class DashboardController extends CrudController
{
    public function index()
    {
        return view('welcome')
        ->with(
            'repositories',
            [
                'OneJAV' => app(OnejavRespository::class),
                'R18' => app(R18Repository::class),
                'XCity Videos' => app(XCityVideoRepository::class),
                'XCity Idols' => app(XCityIdolRepository::class)
            ]
        );
    }
}
