<?php

namespace App\Flickr\Console\Commands\Processes;

use App\Core\Models\State;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use Illuminate\Console\Command;

abstract class BaseProcessCommand extends Command
{
    protected function getProcessItem(string $step, string $modelType = FlickrContact::class): FlickrProcess
    {
        $process = FlickrProcess::byState(State::STATE_INIT)
            ->where('step', $step)
            ->where('model_type', $modelType)
            ->first();

        if (!$process) {
            switch ($step) {
                case FlickrProcess::STEP_PEOPLE_INFO:
                case FlickrProcess::STEP_PEOPLE_PHOTOS:
                case FlickrProcess::STEP_PHOTOSETS_LIST:
                    foreach (FlickrContact::cursor() as $contact) {
                        $contact->process()->create([
                            'step' => $step,
                            'state_code' => State::STATE_INIT,
                        ]);
                    }
                    break;
                case FlickrProcess::STEP_PHOTOSETS_PHOTOS:
                    foreach (FlickrAlbum::cursor() as $album) {
                        $album->process()->create([
                           'step' => $step,
                           'state_code' => State::STATE_INIT,
                        ]);
                    }
                    break;
            }

            $process = FlickrProcess::byState(State::STATE_INIT)
                ->where('step', $step)
                ->first();
        }

        return $process;
    }

}
