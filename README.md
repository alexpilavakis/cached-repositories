# Centralize and cache you application's queries

## Documentation, Installation, and Usage Instructions

First, install the package via Composer:
```
composer require ulex/cached-repositories
```

------------------------------------------
<h2> Service Provider </h2>
<h3>For Laravel</h3>

You should publish the RepositoriesServiceProvider:
```php
php artisan vendor:publish --provider="Ulex\CachedRepositories\RepositoriesServiceProvider" --tag=config
```

Optional: The service provider will automatically get registered. Or you may manually add the service provider in your config/app.php file:
Laravel
```php
'providers' => [
// ...
Ulex\CachedRepositories\RepositoriesServiceProvider::class,
];
```
<h3>For Lumen</h3>

In your `bootstrap/app.php`:
1. Register the provider
```php
$app->register(Ulex\CachedRepositories\RepositoriesServiceProvider::class);
```
2. Register config
```php 
$app->configure('cached-repositories');
```

---------------

<h2> Config </h2>

If config file `cached-repositories.php` was not published copy it to config folder with:
```
cp vendor/ulex/cached-repositories/config/cached-repositories.php config/cached-repositories.php
```

<h2> Create Repository, Interface, Decorator for a Model </h2>

Run the following php artisan command where the argument is your Model name (example Post):
```php
php artisan make:repository Post --all
```
Expected Result:
```php
Repository created successfully.
Interface created successfully.
Decorator created successfully.
Add Model in `models` array in config/cached-repositories.php
```
The following folders will be created in your `app/Repositories` folder (if they don't exist):
```php
Decorators
Eloquent
Interfaces
```
As seen in the result remember to add the Model in `config/cached-repositories.php` :
```php
...
'models' => [
        'User' => App\Models\User::class,
        'Post' => App\Models\Post::class,
]
...
```



## How to use
This package provides an abstract structure that uses the Repository design pattern with caching decorators for you application.

Once installed you can create Repositories for your models that cache the data from your queries.
EloquentRepository is provided and ready to use. Follow the same principle for any data resource you have on your application.

```php
# Example when injecting to a controller 

public function __construct(UserRepositoryInterface $userRepository)
{
    $this->userRepository = $userRepository;
}

...

public function get($name)
{
    //retrieve from db and then cache the result
    $user = $this->userRepository->getBy('name', $userName);
    //retrieve straight from db, don't cache
    $user = $this->userRepository->fromDb()->getBy('name', $userName);
} 
```
## Extending a model's CachingDecorator
For GET functions use `remember` function the same way as in the abstract CachingDecorator. This will ensure that this function is cached properly. 
#### UserCachingDecorator.php
```php
public function getUserInfo($user_id)
{
    return $this->remember(__FUNCTION__, func_get_args());
}
```
<b>Note:</b> Remember to add the cache invalidation of the new function by extending flushGetKeys in the model's CachingDecorator.  
```php
public function flushGetKeys($model, $attributes = null)
{
    $user_id = $model->user_id;
    $key = $this->key('getUserInfo', compact('user_id'));
    parent::flushGetKeys($model, $attributes);
}
```
#### UserRepository.php
Add the query in the model's repository 
```php
public function getUserInfo($user_id)
{
    return $this->model->query()
        ->where('user_id', '=', $user_id)
        ->whereNotNull('something')->get();
}
```
## Contributing

This package is mostly based on [Jeffrey Way](https://twitter.com/jeffrey_way)'s awesome [Laracasts](https://laracasts.com) lessons
when using the repository design pattern on [Laravel From Scratch](https://laracasts.com/series/laravel-6-from-scratch) series.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
