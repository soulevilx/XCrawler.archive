<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Jobs\Traits\HasFlickrMiddleware;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Services\FlickrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OAuth\Common\Http\Exception\TokenResponseException;

abstract class AbstractProcessJob implements ShouldQueue
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
        } catch (FlickrGeneralException) {
            $this->process->setState(State::STATE_FAILED);
            return;
        } catch (TokenResponseException $exception) {
            $this->fail($exception);
            return;
        }

        $this->process->setState(State::STATE_FAILED);
    }

    public function fail($exception = null)
    {
        $this->process->setState(State::STATE_FAILED);
    }

    abstract public function process(): bool;
}
