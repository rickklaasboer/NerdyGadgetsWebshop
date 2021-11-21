<?php

namespace App\Providers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * Register a service
     */
    public function register()
    {
        Request::enableHttpMethodParameterOverride();
        $request = Request::createFromGlobals();

        $this->container->set(Request::class, $request);
    }
}