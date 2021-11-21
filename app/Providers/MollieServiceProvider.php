<?php

namespace App\Providers;

use Mollie\Api\MollieApiClient;

class MollieServiceProvider implements Provider
{
    /**
     * Register a service
     */
    public function register()
    {
        $mollie = new MollieApiClient();
        $mollie->setApiKey(env('MOLLIE_API_KEY'));

        return $mollie;
    }
}