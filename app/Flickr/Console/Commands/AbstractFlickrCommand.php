<?php

namespace App\Flickr\Console\Commands;

use App\Core\Models\State;
use App\Core\Services\Facades\Application;
use App\Flickr\Events\FlickrProcessCompleted;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Repositories\ProcessRepository;
use App\Flickr\Services\FlickrService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;

abstract class AbstractFlickrCommand extends Command
{
    protected FlickrService $service;

    public function handle(FlickrService $service)
    {
        $this->service = $service;
        if ($this->service->getIntegration() === null) {
            return false;
        }
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
        $processes = app(ProcessRepository::class)->getItems(
            $modelType,
            $step,
            State::STATE_INIT,
            Application::getSetting('flickr', 'limit_process_items', 2)
        );

        if ($processes->isEmpty()) {
            Event::dispatch(new FlickrProcessCompleted());
        }

        $data = [];
        foreach ($processes as $process) {
            $data[] = [
                $process->id,
                $process->model_id,
                $process->model_type,
                $process->step,
                'queued',
            ];
        }
        $this->table(
            [
                'id',
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
