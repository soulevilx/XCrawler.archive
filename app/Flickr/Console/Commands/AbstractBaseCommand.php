<?php

namespace App\Flickr\Console\Commands;

use App\Core\Models\State;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Services\FlickrService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractBaseCommand extends Command
{
    protected FlickrService $service;

    public function handle(FlickrService $service)
    {
        $this->service = $service;
        $this->task();
    }

    public function task()
    {
        $task = (string) $this->argument('task');
        $method = 'flickr' . ucfirst($task);

        if (!method_exists($this, $method)) {
            $this->output->warning('Method not found');
            return;
        }

        $this->output->title('Processing ' . $method);
        if ($this->{$method}()) {
            $this->output->info('Completed');
            return;
        }

        $this->output->warning('Uncompleted');
    }

    /**
     * Get the value of a command option.
     *
     * @param string|null $key
     * @return string|array|bool|null
     */
    public function option($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->input->getOptions();
        }

        if (!$this->input->hasOption($key) || !$this->input->getOption($key)) {
            return $default;
        }

        return $this->input->getOption($key);
    }

    protected function getProcessItem(string $step, string $modelType = FlickrContact::class): Collection
    {
        $processes = FlickrProcess::byState(State::STATE_INIT)
            ->where('step', $step)
            ->where('model_type', $modelType)
            ->limit($this->option('limit', 2))
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
                'queued',
            ];
        }
        $this->table(
            [
                'model_id',
                'model_type',
                'step',
                'status',
            ],
            $data
        );

        return $processes;
    }
}
