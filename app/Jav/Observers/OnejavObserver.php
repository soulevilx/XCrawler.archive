<?php

namespace App\Jav\Observers;

use App\Jav\Models\Onejav;

class OnejavObserver
{
    public function created(Onejav $onejav)
    {
        if (!$onejav->movie || !$onejav->movie->requestDownload()->exists()) {
            return;
        }

        if ($onejav->download()) {
            $onejav->movie->requestDownload()->delete();
        }
    }
}
