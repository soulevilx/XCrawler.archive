<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\Traits\HasFlickrMiddleware;
use App\Flickr\Models\FlickrProcess;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class BaseProcessJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use HasFlickrMiddleware;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 60;

    public function __construct(public FlickrProcess $process)
    {
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->process->model->id;
    }

    public function handle()
    {
        $this->init();
        $this->process();
        $this->completed();
    }

    public function fail($exception = null)
    {
        $this->process->setState(State::STATE_FAILED);
    }

    abstract public function process(): bool;

    public function init()
    {
        $this->process->setState(State::STATE_PROCESSING);
    }

    public function completed()
    {
        $this->process->setState(State::STATE_COMPLETED);
    }
}
