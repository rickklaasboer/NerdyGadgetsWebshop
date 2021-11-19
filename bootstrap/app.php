<?php

use App\Support\Facades\Facade;
use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

//Create the PHP-DI container and bind definitions
$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/app.php');

// Build the container
$container = $builder->build();

// Bind exception handler
$container->make(App\Exceptions\Handler::class);

// Bind container to our Facade class
Facade::setAppAccessor($container);

// Finally, return the container
return $container;