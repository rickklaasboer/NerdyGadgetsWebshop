<?php

namespace App\Providers;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class DoctrineServiceProvider implements Provider
{
    /**
     * Register a service
     *
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    public function register(): EntityManager
    {
        $config = $this->getAnnotationMetadataConfiguration();
        $params = $this->getConfig()['db'];

        return EntityManager::create($params, $config);
    }

    /**
     * Get metadata config
     *
     * @return Configuration
     */
    private function getAnnotationMetadataConfiguration(): Configuration
    {
        $config = $this->getConfig();

        return Setup::createAnnotationMetadataConfiguration(
            $config['options']['paths'],
            $config['options']['isDevMode']
        );
    }

    /**
     * Get config file
     *
     * @return mixed
     */
    private function getConfig(): mixed
    {
        return require(__DIR__ . '/../../config/doctrine.php');
    }
}