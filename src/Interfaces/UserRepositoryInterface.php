<?php

namespace Ulex\CachedRepositories\Interfaces;

interface UserRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function getBy($attribute, $value);

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id);

    /**
     * @param $attributes
     * @return mixed
     */
    public function create($attributes);

    /**
     * @param $attributes
     * @return mixed
     */
    public function firstOrCreate($attributes);

    /**
     * @param $attributes
     * @return mixed
     */
    public function updateOrCreate($attributes);

    /**
     * @param $model
     * @param $attributes
     * @return mixed
     */
    public function update($model, $attributes);

    /**
     * @param $model
     */
    public function delete($model);
}