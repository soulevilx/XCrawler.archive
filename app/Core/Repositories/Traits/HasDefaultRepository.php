<?php

namespace App\Core\Repositories\Traits;

use App\Core\Models\State;
use App\Core\Repositories\FilterRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait HasDefaultRepository
{
    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get All
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Get one
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        if (!$model = $this->find($id)) {
            return false;
        }

        return $model->delete();
    }

    public function getItemsByState(int $limit, int $id = null, string $stateCode = State::STATE_INIT): Collection
    {
        if ($id) {
            return $this->model
                ->where(['state_code' => $stateCode])
                ->where(['id' => $id])->get();
        }

        return $this->model->getModel()
            ->where([
                'state_code' => $stateCode,
            ])->limit($limit)->get();
    }

    /**
     * Create
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): Model
    {
        $this->model = $this->model->create($attributes);

        return $this->model;
    }

    /**
     * Update
     *
     * @param array $attributes
     * @return bool
     */
    public function update(array $attributes): bool
    {
        return $this->model->update($attributes);
    }

    public function total(): int
    {
        return $this->model->count();
    }

    public function totalToday(): int
    {
        $now = Carbon::now();
        return $this->model
            ->where('created_at', '>=', $now->startOfDay())
            ->count();
    }

    public function latest()
    {
        return $this->model->latest()->first();
    }

    public function filter(Request $request): FilterRepository
    {
        return (new FilterRepository(clone $this->model, $request));
    }
}
