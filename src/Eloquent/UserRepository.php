<?php

namespace Ulex\CachedRepositories\Eloquent;

use App\User;
use Ulex\CachedRepositories\Interfaces\UserRepositoryInterface;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    protected $model;

    /**
     * StoryRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Example Methods
     */
    public function isAdmin()
    {
        // TODO: Implement isAdmin() method.
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getByEmail($email)
    {
        return $this->getBy('email', $email);
    }
}
