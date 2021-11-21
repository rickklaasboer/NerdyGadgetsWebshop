<?php

use App\Support\Facades\Facade;
use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

// Build the container
$container = (new ContainerBuilder())->build();

// Register all providers
foreach ((require BASE_PATH . '/config/app.php')['providers'] as $provider) {
    (new $provider($container))->register();
}

// Bind exception handler
$container->make(App\Exceptions\Handler::class);

// Bind container to our Facade class
Facade::setAppAccessor($container);

// Finally, return the container
return $container;