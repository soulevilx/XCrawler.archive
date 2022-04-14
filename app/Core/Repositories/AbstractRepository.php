<?php

namespace App\Core\Repositories;

use App\Core\Models\State;
use App\Flickr\Models\FlickrProcess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractRepository
{
    /**
     * Get All
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->getModel()->all();
    }

    /**
     * Get one
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->getModel()->find($id);
    }

    public function getItemsByState(int $limit, int $id = null, string $stateCode = State::STATE_INIT): Collection
    {
        if ($id) {
            return $this->getModel()
                ->where(['state_code' => $stateCode])
                ->where(['id' => $id])->get();
        }

        return $this->getModel()->getModel()
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
    public function create(array $attributes)
    {
        $model = $this->getModel()->create($attributes);
        $this->setModel($model);

        return $this->getModel();
    }

    public function firstOrCreate(array $conditions, array $attributes): Model
    {
        return $this->getModel()->withTrashed()->firstOrCreate($conditions, $attributes);
    }

    public function updateOrCreate(array $conditions, array $attributes): Model
    {
        return $this->getModel()->withTrashed()->updateOrCreate($conditions, $attributes);
    }

    /**
     * Update
     *
     * @param array $attributes
     * @return bool|mixed
     */
    public function update(array $attributes)
    {
        return $this->getModel()->update($attributes);
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        if (!$result = $this->find($id)) {
            return false;
        }

        $result->delete();

        return true;
    }

    abstract public function getModel();

    abstract public function setModel($model);

    public function addPhotos(Collection $photos)
    {
        foreach ($photos as $photo) {
            $this->getModel()->photos()->firstOrCreate([
                'id' => $photo['id'],
                'owner' => $photo['owner'],
            ], $photo);
        }

        $this->getModel()->processes()->create([
            'step' => FlickrProcess::STEP_PEOPLE_PHOTOS,
        ]);
    }
}
