<?php

namespace App\Providers;

/** Adjust your Model's namespace */
use App\User;
use Illuminate\Support\ServiceProvider;
use Ulex\CachedRepositories\Decorators\UserCachingDecorator;
use App\Repositories\Eloquent\UserRepository;
use Ulex\CachedRepositories\Interfaces\UserRepositoryInterface;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /** Uncomment and Register your Repositories */
        /*$this->app->singleton(UserRepositoryInterface::class, function () {
            $user = new User();
            $baseRepo = new UserRepository($user);
            return new UserCachingDecorator($baseRepo, $this->app['cache.store'], $user);
        });*/
    }
}
