<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Services\FlickrService;

abstract class AbstractProcessJob extends AbstractLimitJob
{
    protected FlickrService $service;

    public function __construct(public FlickrProcess $process)
    {
    }

    public function handle(FlickrService $service)
    {
        $this->service = $service;
        $this->process->setState(State::STATE_PROCESSING);

        if ($this->process->model && $this->process()) {
            $this->process->setState(State::STATE_COMPLETED);
        }
    }

    public function failed()
    {
        $this->process->setState(State::STATE_FAILED);
    }

    abstract public function process(): bool;
}
