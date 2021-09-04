<?php

namespace App\Jav\Http\Controllers;

use App\Jav\Models\Onejav;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class OnejavController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function download(Onejav $onejav)
    {
        if ($onejav->download()) {
            return response(null, 200);
        }

        return response(null, 404);
    }
}
