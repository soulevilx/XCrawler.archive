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
        $onejav->download();
    }
}
