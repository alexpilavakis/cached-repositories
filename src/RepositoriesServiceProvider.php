<?php

namespace Ulex\CachedRepositories;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Ulex\CachedRepositories\Console\Commands\CachingDecoratorMakeCommand;
use Ulex\CachedRepositories\Console\Commands\InterfaceMakeCommand;
use Ulex\CachedRepositories\Console\Commands\RepositoryMakeCommand;

class RepositoriesServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        if (function_exists('config_path')) { // function not available and 'publish' not relevant in Lumen
            $this->publishes([__DIR__ . '/../config/cached-repositories.php' => config_path('cached-repositories.php')], 'config');
        }
        if ($this->app->runningInConsole()) {
            $this->commands([
                RepositoryMakeCommand::class,
                InterfaceMakeCommand::class,
                CachingDecoratorMakeCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $models = $this->app->config['cached-repositories.models'];
        $namespaces = $this->app->config['cached-repositories.namespaces'];
        try{
            $cache = $this->app['cache.store'];
            $alive = $cache->connection();
        }catch(\Throwable $exception){
            $alive = false;
        }
        foreach ($models as $name => $class) {
            $interface = $namespaces['interfaces'] . "\\" . $name . "RepositoryInterface";
            $decorator = $namespaces['decorators'] . "\\" . $name . "CachingDecorator";
            $repository = $namespaces['eloquent'] . "\\" . $name . "Repository";
            $this->app->singleton($interface, function () use ($class, $decorator, $repository, $alive, $cache) {
                $model = new $class();
                $baseRepo = new $repository($model);
                return $alive ? new $decorator($baseRepo, $cache, $model) : $baseRepo;
            });
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        $provides = [];
        $models = $this->app->config['cached-repositories.models'];
        $namespaces = $this->app->config['cached-repositories.namespaces'];
        foreach ($models as $name => $class) {
            $provides[] = $namespaces['interfaces'] . "\\" . $name . "RepositoryInterface";
        }
        return $provides;
    }
}
