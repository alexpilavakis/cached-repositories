<?php

namespace Ulex\CachedRepositories\Eloquent;

use Ulex\CachedRepositories\Interfaces\CachingDecoratorInterface;

abstract class EloquentRepository implements CachingDecoratorInterface
{
    /**
     * @var
     */
    protected $model;

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function getBy($attribute, $value)
    {
        return $this->model->where($attribute, '=', $value)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function create($attributes)
    {
        return $this->model::create($attributes);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function firstOrCreate($attributes)
    {
        return $this->model::firstOrCreate($attributes);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function updateOrCreate($attributes)
    {
        return $this->model::updateOrCreate($attributes);
    }

    /**
     * @param $model
     * @param $attributes
     * @return mixed
     */
    public function update($model, $attributes)
    {
        return $model->update($attributes);
    }

    /**
     * @param $model
     */
    public function delete($model)
    {
        $model->delete();
    }
}