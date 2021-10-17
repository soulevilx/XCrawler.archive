<?php

namespace App\Jav\Observers;

use App\Jav\Models\Onejav;
use App\Jav\Services\OnejavService;

class OnejavObserver
{
    public function created(Onejav $onejav)
    {
        if (!$onejav->movie || !$onejav->movie->requestDownload()->exists()) {
            return;
        }

        if (app(OnejavService::class)->download($onejav)) {
            $onejav->movie->requestDownload()->delete();
        }
    }
}
