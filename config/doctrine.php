<?php

return [
    'db' => [
        'driver' => 'pdo_mysql',
        'user' => env('DB_USER'),
        'password' => env('DB_PASSWORD'),
        'dbname' => env('DB_DATABASE'),
    ],

    'options' => [
        'isDevMode' => env('APP_ENV') === 'dev',
        'paths' => [
            __DIR__ . '/../app/Entities',
        ],
    ],
];
