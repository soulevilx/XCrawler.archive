<?php

namespace App\Core\Repositories;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * Create
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): Model
    {
        $model = $this->getModel()->create($attributes);
        $this->setModel($model);

        return $this->getModel();
    }

    public function firstOrCreate(array $conditions, array $attributes): Model
    {
        return $this->getModel()->firstOrCreate($conditions, $attributes);
    }

    public function updateOrCreate(array $conditions, array $attributes): Model
    {
        return $this->getModel()->updateOrCreate($conditions, $attributes);
    }

    /**
     * Update
     *
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, array $attributes)
    {
        if (!$result = $this->find($id)) {
            return false;
        }

        $result->update($attributes);

        return $result;
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
}
