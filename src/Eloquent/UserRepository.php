<?php

namespace App\Repositories\Eloquent;

/** Adjust your Model's namespace */
use App\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Ulex\CachedRepositories\Eloquent\EloquentRepository;

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
