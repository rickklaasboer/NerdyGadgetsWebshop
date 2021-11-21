<?php

namespace App\Providers;

use Mollie\Api\MollieApiClient;

class MollieServiceProvider extends ServiceProvider
{
    /**
     * Register a service
     */
    public function register()
    {
        $mollie = new MollieApiClient();
        $mollie->setApiKey(env('MOLLIE_API_KEY'));

        $this->container->set(MollieApiClient::class, $mollie);
    }
}