<?php

namespace App\Core\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected $model;

    /**
     * Get All
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
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

    public function firstOrCreate(array $conditions, array $attributes): Model
    {
        return $this->model->firstOrCreate($conditions, $attributes);
    }

    public function updateOrCreate(array $conditions, array $attributes): Model
    {
        return $this->model->updateOrCreate($conditions, $attributes);
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

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }
}
