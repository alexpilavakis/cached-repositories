<?php

/*
 * This file is part of cached-repositories.
 *
 * (c) Alexandros Pilavakis <alexpilavakis@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Namespaces of you Repositories sub folders
    |--------------------------------------------------------------------------
    |
    |
    */
    'namespaces' => [
        'interfaces' => 'App\Repositories\Interfaces',
        'decorators' => 'App\Repositories\Decorators',
        'eloquent' => 'App\Repositories\Eloquent'
    ],
    /*
    |--------------------------------------------------------------------------
    | Models that need Repository Binding
    |--------------------------------------------------------------------------
    |
    |
    */

    'models' => [
        'User' => \App\User::class
    ]
];
