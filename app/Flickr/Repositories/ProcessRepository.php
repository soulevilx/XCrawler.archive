<?php

namespace App\Flickr\Repositories;

use App\Core\Models\State;
use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Flickr\Models\FlickrProcess;
use Illuminate\Support\Collection;

class ProcessRepository
{
    use HasDefaultRepository;

    public function __construct(protected FlickrProcess $model)
    {
    }

    public function getItems(string $modelType, string $step, string $stateCode = State::STATE_INIT, int $limit = 5): Collection
    {
        return $this->model->where('state_code', $stateCode)
            ->where('step', $step)
            ->where('model_type', $modelType)
            ->limit($limit)
            ->get();
    }
}
