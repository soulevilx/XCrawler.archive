<?php

namespace App\Jav\Http\Controllers;

use App\Jav\Repositories\OnejavRespository;
use App\Jav\Repositories\R18Repository;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class R18Controller extends CrudController
{
    public function index(Request $request)
    {
        return view('movies.index')
            ->with('movies', app(R18Repository::class)->filter($request)->get());
    }
}
