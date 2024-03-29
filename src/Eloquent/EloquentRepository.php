<?php

namespace Ulex\CachedRepositories\Eloquent;

use Ulex\CachedRepositories\Interfaces\CachingDecoratorInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Closure;

abstract class EloquentRepository implements CachingDecoratorInterface
{
    /**
     * @var
     */
    protected $model;

    /**
     * @return $this
     */
    public function fromDb()
    {
        return $this;
    }

    /** ################################################ Single ################################################ */

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
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
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function findBy($attribute, $value)
    {
        return $this->model->where($attribute, '=', $value)->first();
    }

    /**
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function checkIfExists($attribute, $value)
    {
        return $this->model->where($attribute, '=', $value)->exists();
    }

    /** ################################################ Get Collection ################################################ */

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * @param array $conditions
     * @return mixed
     */
    public function getByConditions(array $conditions)
    {
        return $this->model->where($conditions)->get();
    }

    /** ################################################ Modify ################################################ */

    /**
     * @param $attributes
     * @return mixed
     */
    public function create($attributes)
    {
        return $this->model::create($attributes);
    }

    /**
     * Example:
     * $attributes = [
     *      [
     *          'attribute_1' => 'some_value',
     *          'attribute_2' => 'some_value',
     *          ...
     *      ],
     *      [
     *          'attribute_1' => 'some_value',
     *          'attribute_2' => 'some_value',
     *      ],
     *      ...
     * ];
     *
     * @param array $attributes
     */
    public function createMany(array $attributes)
    {
        if ($this->model->timestamps) {
            $date = Carbon::now();
            $attributes = array_map($this->mapValues($date), $attributes);
        }
        DB::table($this->model->getTable())->insertOrIgnore($attributes);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function firstOrCreate($attributes)
    {
        return $this->model->firstOrCreate($attributes);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function updateOrCreate($attributes)
    {
        return $this->model->updateOrCreate($attributes);
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
     * @param array $conditions
     * @param array $attributes
     * @return bool|int
     */
    public function updateByConditions(array $conditions, array $attributes)
    {
        return $this->model->where($conditions)->update($attributes);
    }

    /**
     * @param $model
     */
    public function delete($model)
    {
        $model->delete();
    }

    /**
     * @param array $conditions
     */
    public function deleteByConditions(array $conditions)
    {
    }

    /**
     * @param $date
     *
     * @return Closure
     */
    protected function mapValues($date)
    {
        return function ($item) use ($date) {
            $item['created_at'] = $item['updated_at'] = $date;
            return $item;
        };
    }
}
