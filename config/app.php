<?php

return [
    'providers' => [
        App\Providers\ConfigServiceProvider::class,
        App\Providers\TwigServiceProvider::class,
        App\Providers\DoctrineServiceProvider::class,
        App\Providers\RequestServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\MollieServiceProvider::class,
        App\Providers\TranslationServiceProvider::class,
    ],
];
