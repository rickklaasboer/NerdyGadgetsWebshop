<?php

namespace App\Providers;

use DI\Container;

class ServiceProvider
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Register a new service
     */
    public function register()
    {
        //
    }
}