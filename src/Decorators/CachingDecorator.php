<?php

namespace Ulex\CachedRepositories\Decorators;

use Closure;
use ReflectionClass;
use Illuminate\Contracts\Cache\Repository as Cache;
use Ulex\CachedRepositories\Interfaces\CachingDecoratorInterface;

abstract class CachingDecorator implements CachingDecoratorInterface
{
    /** @var CachingDecoratorInterface */
    protected $repository;

    /** @var Cache */
    protected $cache;

    /** @var */
    protected $model;

    /** @var bool */
    protected $cacheForever = false;

    const CACHE_TAG_COLLECTION = 'collection';

    /**
     * @return int
     */
    protected function ttl(): int
    {
        return app()->config['cached-repositories.ttl.default'];
    }

    /**
     * NOTE: Cache tags are not supported when using the `file` or `database` cache drivers.
     * @return array
     */
    protected function tag(): array
    {
        return [
            (new ReflectionClass($this->model))->getShortName()
        ];
    }

    /**
     * Cache with multiple tags that can be invalidated
     * @param array $extraTags
     * @return array
     */
    protected function tags(array $extraTags): array
    {
        return array_merge($this->tag(), $extraTags);
    }

    /**
     * Flush all 'get' keys for this model instance along with any collections
     *
     * @param $model
     * @param array|null $attributes
     */
    public function flushGetKeys($model, $attributes = null)
    {
        if (isset($model->id)) {
            $this->forget("find:{$model->id}");
            $this->forget("findOrFail:{$model->id}");
        }
        $attributes = $attributes ?? $model->getAttributes();
        $this->flushAttributes($attributes);

        $this->flushCollections();
    }


    /**
     * @param array $attributes
     */
    protected function flushAttributes(array $attributes)
    {
        if (empty($attributes)) {
            return;
        }
        /** when timestamps() is used */
        unset($attributes['created_at']);
        unset($attributes['updated_at']);
        /** when softDeletes() is used */
        unset($attributes['deleted_at']);

        foreach ($attributes as $attribute => $value) {
            $this->flushFunction('findBy', [$attribute, $value]);
            $this->flushFunction('checkIfExists', [$attribute, $value]);
        }
    }

    protected function flushCollections()
    {
        $this->flushTag(self::CACHE_TAG_COLLECTION);
    }

    /**
     * @param null $tag
     */
    protected function flushTag($tag = null)
    {
        $tag = $tag ?? $this->tag();
        $this->cache->tags($tag)->flush();
    }

    /**
     * @param string $function
     * @param $attributes
     * @param null $tags
     */
    public function flushFunction(string $function, $attributes = null, $tags = null)
    {
        $key = $this->key($function, $attributes);
        $tags = $tags ?? $this->tag();
        $this->forget($key, $tags);
    }

    /**
     * @param $key
     * @param null $tags
     * @return bool
     */
    public function forget($key, $tags = null)
    {
        $tags = $tags ?? $this->tag();
        return $this->cache->tags($tags)->forget($key);
    }

    /**
     * @param $function
     * @param null $arguments
     * @return string
     */
    protected function key($function, $arguments = null)
    {
        if (empty($arguments)) {
            return $function;
        }
        if (isset($arguments[0]) && is_array($arguments[0])) {
            $key = $function;
            foreach ($arguments[0] as $name => $value) {
                $key .= ':' . $name . ':' . $value;
            }
            return $key;
        }
        return sprintf('%s:%s', $function, implode(':', $arguments));
    }

    /**
     * @param string $function
     * @param $arguments
     * @param array|null $tags
     * @return array|mixed
     */
    protected function remember(string $function, $arguments, $tags = null)
    {
        $key = $this->key($function, $arguments);
        $closure = $this->closure($function, $arguments);
        $tags = $tags ?? $this->tag();
        if ($this->cacheForever) {
            return $this->cache->tags($tags)->rememberForever($key, $closure);
        }
        return $this->cache->tags($tags)->remember($key, $this->ttl(), $closure);
    }

    /**
     * @param $function
     * @param $arguments
     * @return Closure
     */
    private function closure($function, $arguments)
    {
        $repository = $this->getRepository();
        return function () use ($function, $arguments, $repository) {
            return $repository->$function(...$arguments);
        };
    }

    /**
     * @return CachingDecoratorInterface
     */
    protected function getRepository()
    {
        return $this->repository;
    }

    /** ################################################ Get single ################################################ */

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->remember(__FUNCTION__, func_get_args());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->remember(__FUNCTION__, func_get_args());
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
        return $this->remember(__FUNCTION__, func_get_args());
    }

    /** ################################################ Get Collection ################################################ */

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->remember(__FUNCTION__, func_get_args(), $this->tags([self::CACHE_TAG_COLLECTION]));
    }

    /**
     * @param array $conditions
     * @return array|mixed
     */
    public function getByConditions(array $conditions)
    {
        return $this->remember(__FUNCTION__, func_get_args(), $this->tags([self::CACHE_TAG_COLLECTION]));
    }

    /** ################################################ Modify ################################################ */

    /**
     * @param $attributes
     * @return mixed
     */
    public function create($attributes)
    {
        $model = $this->getRepository()->create($attributes);
        $this->flushGetKeys($model);
        return $model;
    }

    /**
     * @param array $attributes
     */
    public function createMany(array $attributes)
    {
        $this->flushCollections();
        $this->repository->createMany($attributes);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function firstOrCreate($attributes)
    {
        $model = $this->getRepository()->firstOrCreate($attributes);
        $this->flushGetKeys($model);
        return $model;
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function updateOrCreate($attributes)
    {
        $model =  $this->getRepository()->updateOrCreate($attributes);
        $this->flushGetKeys($model);
        return $model;
    }

    /**
     * @param $model
     * @param $attributes
     * @return mixed
     */
    public function update($model, $attributes)
    {
        $this->flushGetKeys($model);
        return $this->getRepository()->update($model, $attributes);
    }

    /**
     * @param array $conditions
     * @param array $attributes
     * @return bool
     */
    public function updateByConditions(array $conditions, array $attributes)
    {
        $result = $this->repository->updateByConditions($conditions, $attributes);
        if ($result) {
            $models = $this->getByConditions($conditions);
            foreach ($models as $model) {
                $this->flushGetKeys($model, $attributes);
            }
        }
        return $result;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function delete($model)
    {
        $this->flushGetKeys($model);
        return $this->getRepository()->delete($model);
    }

    /**
     * @param array $conditions
     * @return void
     */
    public function deleteByConditions(array $conditions)
    {
        $models = $this->getByConditions($conditions);
        foreach ($models as $model) {
            $this->delete($model);
        }
    }
}
