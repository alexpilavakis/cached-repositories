<?php

namespace App\Repositories\Decorators;

/** Adjust your Model's namespace */
use App\User;
use Illuminate\Contracts\Cache\Repository as Cache;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Ulex\CachedRepositories\Interfaces\CachingDecoratorInterface;

class UserCachingDecorator extends CachingDecorator implements UserRepositoryInterface
{
    /**
     * UserCachingDecorator constructor.
     * @param CachingDecoratorInterface $repository
     * @param Cache $cache
     * @param User $model
     */
    public function __construct(CachingDecoratorInterface $repository, Cache $cache, User $model)
    {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->model = $model;
    }
}
