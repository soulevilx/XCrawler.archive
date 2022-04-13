<?php

namespace App\Flickr\Models\Traits;

use App\Flickr\Models\FlickrProcess;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * @property-read FlickrProcess[]|Collection $processes
 */
trait HasProcesses
{
    public function processes(): MorphMany
    {
        return $this->morphMany(FlickrProcess::class, 'model');
    }

    public function processStep(string $step)
    {
        return $this->processes()->where('step', $step)->latest()->first();
    }
}
