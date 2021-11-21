<?php

namespace App\Providers;

use App\Support\Config;

class ConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->container->set(Config::class, new Config());
    }
}