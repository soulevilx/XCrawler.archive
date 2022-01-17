<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Services\FlickrService;
use OAuth\Common\Http\Exception\TokenResponseException;

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

        try {
            if ($this->process->model && $this->process()) {
                $this->process->setState(State::STATE_COMPLETED);
                return;
            }
        } catch (FlickrGeneralException | TokenResponseException $exception) {
            $this->fail($exception);
            return;
        }

        $this->process->setState(State::STATE_FAILED);
    }

    public function fail($exception = null)
    {
        $this->process->setState(State::STATE_FAILED);

        if ($this->job) {
            $this->job->fail($exception);
        }
    }

    abstract public function process(): bool;
}
