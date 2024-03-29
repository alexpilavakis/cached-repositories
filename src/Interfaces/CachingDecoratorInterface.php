<?php

namespace Ulex\CachedRepositories\Interfaces;

interface CachingDecoratorInterface
{
    /**
     * @return $this
     */
    public function fromDb();

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id);

    /**
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function findBy($attribute, $value);

    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function checkIfExists($attribute, $value);

    /**
     * @param array $conditions
     * @return mixed
     */
    public function getByConditions(array $conditions);

    /**
     * @param $attributes
     * @return mixed
     */
    public function create($attributes);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function createMany(array $attributes);

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
     * @param array $conditions
     * @param array $attributes
     * @return bool|int
     */
    public function updateByConditions(array $conditions, array $attributes);

    /**
     * @param $model
     * @return mixed
     */
    public function delete($model);

    /**
     * @param array $conditions
     * @return void
     */
    public function deleteByConditions(array $conditions);
}
