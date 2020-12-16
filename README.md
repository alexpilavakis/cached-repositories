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
php artisan vendor:publish --provider="Ulex\CachedRepositories\RepositoriesServiceProvider"
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

In your `bootstrap/app.php` add this:
```
$app->register(Ulex\CachedRepositories\RepositoriesServiceProvider::class);
```

---------------

<h2> Config </h2>

If config file `cached-repositories.php` was not published copy it to config folder with:
```
cp vendor/ulex/cached-repositories/config/cached-repositories.php config/cached-repositories.php
```

<h2> Example </h2>

<b>!! Create Repositories folder IF not already present</b>:
```php
mkdir -p app/Repositories
```
Copy the User example and adjust it to your application:
```php
cp -r vendor/ulex/cached-repositories/example/* app/Repositories/
```


## What It Does
This package provides an abstract structure that uses the Repository design pattern with caching decorators for you application.

Once installed you can create Repositories for your models that cache the data from your queries.
EloquentRepository is provided and ready to use. Follow the same principle for any data resource you have on your application.

```php
// Example when injecting to a controller 
/*
* @param UserRepositoryInterface $siteRepository
*/
public function __construct(UserRepositoryInterface $userRepository)
{
    $this->userRepository = $userRepository;
}

...

/** @var User $user */
$user = $this->userRepository->getBy('name', $userName);
```

## Contributing

This package is mostly based on [Jeffrey Way](https://twitter.com/jeffrey_way)'s awesome [Laracasts](https://laracasts.com) lessons
when using the repository design pattern on [Laravel From Scratch](https://laracasts.com/series/laravel-6-from-scratch) series.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
