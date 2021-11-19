<?php

namespace App\Support\Facades;

use Psr\Container\ContainerInterface;
use RuntimeException;

class Facade
{
    protected static ContainerInterface $app;

    /**
     * Get the facade accessor
     *
     * @return string
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException("Facade has not implemented getFacadeAccessor");
    }

    /**
     * Set the app container
     *
     * @param ContainerInterface $app
     */
    public static function setAppAccessor(ContainerInterface $app)
    {
        static::$app = $app;
    }

    /**
     * Set the app container
     *
     * @return ContainerInterface
     */
    public static function getAppAccessor(): ContainerInterface
    {
        return static::$app;
    }
}