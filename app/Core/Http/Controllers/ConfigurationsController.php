<?php

namespace App\Core\Http\Controllers;

use App\Core\Models\Application;

class ConfigurationsController extends BaseController
{
    public function index()
    {
        return response()->view('pages.configurations.index', [
            'configurations' => Application::all(),
        ]);
    }

    public function update()
    {

    }
}
