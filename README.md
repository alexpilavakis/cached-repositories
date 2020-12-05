# Centralize and cache you application's queries

## Documentation, Installation, and Usage Instructions

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
