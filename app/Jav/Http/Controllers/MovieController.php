<?php

namespace App\Jav\Http\Controllers;

use App\Jav\Repositories\MovieRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class MovieController extends CrudController
{
    public function index(Request $request)
    {
        return view('movies.index')

            ->with('movies', app(MovieRepository::class)->filter($request)->get());

    }
}
