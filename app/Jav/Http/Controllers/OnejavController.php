<?php

namespace App\Jav\Http\Controllers;

use App\Jav\Repositories\OnejavRespository;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class OnejavController extends CrudController
{
    public function index(Request $request)
    {
        return view('movies.index')
            ->with('movies', app(OnejavRespository::class)->filter($request)->get());
    }
}
