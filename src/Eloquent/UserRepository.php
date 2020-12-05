<?php

namespace Ulex\CachedRepositories\Eloquent;

/** Adjust your Model's namespace */
use App\User;
use Ulex\CachedRepositories\Interfaces\UserRepositoryInterface;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    protected $model;

    /**
     * UserRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
