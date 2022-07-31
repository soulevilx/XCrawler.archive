<?php

namespace App\Core\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

class DashboardController extends CrudController
{
    public function index()
    {
        return view('welcome');
    }
}
