<?php

namespace App\Jav\Http\Controllers;

use App\Jav\Models\Onejav;
use App\Jav\Services\OnejavService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class OnejavController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function download(Onejav $onejav, OnejavService $service)
    {
        if ($service->download($onejav) && $onejav->movie) {
            return redirect()->route('movie.show', ['movie' => $onejav->movie]);
        }

        return redirect()->route('movies.index');
    }
}
