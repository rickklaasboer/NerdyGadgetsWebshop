<?php

namespace App\Providers;

use App\Translation\Translation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register a service
     */
    public function register()
    {
        // Register session before initializing the Translation service
        $request = tap($this->container->get(Request::class), function (Request $request) {
            $request->setSession(new Session());
        });

        $this->container->set(Translation::class, new Translation($request));
    }
}