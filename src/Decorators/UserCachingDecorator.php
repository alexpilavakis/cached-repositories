<?php

namespace Ulex\CachedRepositories\Decorators;

use App\User;
use Illuminate\Contracts\Cache\Repository as Cache;
use Ulex\CachedRepositories\Interfaces\CachingDecoratorInterface;
use Ulex\CachedRepositories\Interfaces\UserRepositoryInterface;

class UserCachingDecorator extends CachingDecorator implements UserRepositoryInterface
{
    /**
     * CachingDecorator constructor.
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
