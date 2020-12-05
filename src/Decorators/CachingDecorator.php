<?php

namespace Ulex\CachedRepositories\Decorators;

use Illuminate\Contracts\Cache\Repository as Cache;
use ReflectionClass;
use Ulex\CachedRepositories\Interfaces\CachingDecoratorInterface;

abstract class CachingDecorator implements CachingDecoratorInterface
{
    /**
     * @var CachingDecoratorInterface
     */
    protected $repository;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var
     */
    protected $model;

    /**
     * CachingDecorator constructor.
     * @param CachingDecoratorInterface $repository
     * @param Cache $cache
     * @param $model
     */
    public function __construct(CachingDecoratorInterface $repository, Cache $cache, $model)
    {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->model = $model;
    }

    /**
     * NOTE: Cache tags are not supported when using the `file` or `database` cache drivers.
     * @return string
     */
    private function tag(): string
    {
        return (new ReflectionClass($this->model))->getShortName();
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->cache->tags($this->tag())->remember('all', 60, function () {
            return $this->repository->getAll();
        });
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->cache->tags($this->tag())->remember($id, 60, function () use ($id) {
            return $this->repository->getById($id);
        });
    }

    /**
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function getBy($attribute, $value)
    {
        return $this->cache->tags($this->tag())->remember($attribute . ':' . $value, 60,
            function () use ($attribute, $value) {
                return $this->repository->getBy($attribute, $value);
            });
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->cache->tags($this->tag())->remember($id, 60, function () use ($id) {
            return $this->repository->findOrFail($id);
        });
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function create($attributes)
    {
        $this->cache->tags($this->tag())->flush();
        return $this->repository->create($attributes);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function firstOrCreate($attributes)
    {
        return $this->repository->firstOrCreate($attributes);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function updateOrCreate($attributes)
    {
        return $this->repository->updateOrCreate($attributes);
    }

    /**
     * @param $model
     * @param $attributes
     * @return mixed
     */
    public function update($model, $attributes)
    {
        return $this->repository->update($model, $attributes);
    }

    /**
     * @param $model
     * @return mixed
     */
    public function delete($model)
    {
        return $this->repository->delete($model);
    }

}
