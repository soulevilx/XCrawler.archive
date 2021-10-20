<?php

namespace App\Flickr\Console\Commands\Traits;

use App\Core\Models\State;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use Illuminate\Database\Eloquent\Collection;

trait HasProcesses
{
    protected function getProcessItem(string $step, string $modelType = FlickrContact::class): Collection
    {
        $processes = FlickrProcess::byState(State::STATE_INIT)
            ->where('step', $step)
            ->where('model_type', $modelType)
            ->limit(2)
            ->get();

        if ($processes->isEmpty()) {
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

            $processes = FlickrProcess::byState(State::STATE_INIT)
                ->where('step', $step)
                ->limit(4)
                ->get();
        }

        $data = [];
        foreach ($processes as $process) {
            $data[] = [
                $process->model_id,
                $process->model_type,
                $process->step,
            ];
        }
        $this->table(
            [
                'model_id',
                'model_type',
                'step',
            ],
            $data
        );

        return $processes;
    }
}
