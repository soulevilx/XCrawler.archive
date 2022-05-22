<?php

namespace App\Core\Services;

use App\Core\Models\ClientRequest;
use App\Core\Models\Log;
use App\Core\Models\State;
use App\Core\Services\Facades\Application;
use App\Flickr\Models\FlickrProcess;

class CleanupService
{
    public function cleanup()
    {
        $this->flickrProcesses();
        $this->logs();
        $this->clientRequests();
    }

    protected function flickrProcesses()
    {
        FlickrProcess::where(['state_code' => State::STATE_COMPLETED])->forceDelete();
    }

    public function logs()
    {
        Log::where('created_at - now()', '>', Application::getInt('core', 'prune_logs_interval', 7))->delete();
    }

    public function clientRequests()
    {
        ClientRequest::where('is_succeed', true)
            ->where('created_at - now()', '>', Application::getInt('core', 'prune_client_requests_interval', 7))
            ->delete();
    }
}
