<?php

namespace App\Providers;

use App\Auth\Auth;

class AuthServiceProvider implements Provider
{
    /**
     * Register a service
     *
     * @return Auth
     */
    public function register(): Auth
    {
        return new Auth();
    }
}