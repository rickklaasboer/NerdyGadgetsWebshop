<?php

namespace App\Support;

use Dotenv\Dotenv;

/**
 * Class LoadEnvironmentVariables
 *
 * @package App\Support
 * @author Rick Klaasboer <rick@klaasboer.org>
 */
class LoadEnvironmentVariables
{
    /**
     * @var bool
     */
    protected static bool $bootstrapped = false;

    /**
     * Bootstrap (allows static constructing)
     *
     * @param $dir
     */
    public function bootstrap($dir)
    {
        $dotenv = Dotenv::createImmutable($dir);
        $dotenv->load();

        static::$bootstrapped = true;
    }

    /**
     * Check whether the environment has been bootstrapped
     *
     * @return bool
     */
    public static function hasBeenBootstrapped()
    {
        return static::$bootstrapped;
    }
}