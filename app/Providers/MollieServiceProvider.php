<?php

namespace App\Providers;

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\MollieApiClient;

class MollieServiceProvider extends ServiceProvider
{
    /**
     * Register a service
     */
    public function register()
    {
        try {
            $mollie = new MollieApiClient();

            // Might throw an exception when the key isn't set in environment.
            $mollie->setApiKey(env('MOLLIE_API_KEY'));

            $this->container->set(MollieApiClient::class, $mollie);
        } catch (ApiException) {
            $this->container->set(MollieApiClient::class, null);
        }
    }
}