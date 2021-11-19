<?php

namespace App\Providers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class RequestServiceProvider implements Provider
{
    /**
     * Register a service
     *
     * @return Request
     */
    public function register(): Request
    {
        Request::enableHttpMethodParameterOverride();

        return Request::createFromGlobals();
    }
}