<?php

namespace App\Providers;

use App\Support\Config;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class DoctrineServiceProvider extends ServiceProvider
{
    /**
     * Register a service
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function register()
    {
        $config = $this->getAnnotationMetadataConfiguration();
        $em = EntityManager::create(
            $this->config('doctrine.db'),
            $config
        );

        $this->container->set(EntityManager::class, $em);
    }

    /**
     * Get metadata config
     *
     * @return Configuration
     */
    private function getAnnotationMetadataConfiguration(): Configuration
    {
        return Setup::createAnnotationMetadataConfiguration(
            ...$this->config('doctrine.options')
        );
    }

    /**
     * Get instance of config manager
     *
     * @param mixed ...$vars
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function config($key)
    {
        return $this->container->get(Config::class)->get($key);
    }
}