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
        $fileName = $service->download($onejav);
        if (!$fileName) {
            session()->flash(
                'messages',
                [
                    ['message' => 'Can not download', 'type' => 'danger'],
                ]
            );
        } else {
            session()->flash(
                'messages',
                [
                    ['message' => 'Download completed:  ' . $fileName, 'type' => 'primary'],
                ]
            );
        }

        return redirect()->route('movie.show', ['movie' => $onejav->movie]);
    }
}
