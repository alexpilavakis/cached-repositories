<?php

namespace Ulex\CachedRepositories;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

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
        foreach ($models as $name => $class) {
            $interface = $namespaces['interfaces'] . "\\" . $name . "RepositoryInterface";
            $decorator = $namespaces['decorators'] . "\\" . $name . "CachingDecorator";
            $repository = $namespaces['eloquent'] . "\\" . $name . "Repository";
            $this->app->singleton($interface, function () use ($name, $class, $decorator, $repository) {
                $model = new $class();
                $baseRepo = new $repository($model);
                return new $decorator($baseRepo, $this->app['cache.store'], $model);
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
