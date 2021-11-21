<?php

namespace App\Providers;

use App\Auth\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register a service
     */
    public function register()
    {
        $this->container->set(Auth::class, new Auth());
    }
}