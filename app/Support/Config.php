<?php

namespace App\Support;

use Adbar\Dot;

class Config
{
    // Base config path
    const CONFIG_PATH = BASE_PATH . '/config/';

    protected Dot $config;

    protected array $files;

    public function __construct()
    {
        $this->files = $this->getFilesFromDirectory(self::CONFIG_PATH);

        $this->prepare();
    }

    /**
     * Get from config
     *
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null): mixed
    {
        return $this->config->get($key, $fallback);
    }

    /**
     * Prepare array for dot notation
     */
    protected function prepare(): void
    {
        $contents = [];

        foreach ($this->files as $key => $value) {
            $contents[str_replace('.php', '', $value)] = require self::CONFIG_PATH . $value;
        }

        $this->config = new Dot($contents);
    }

    /**
     * Get files from directory
     *
     * @param $dir
     * @return array
     */
    protected function getFilesFromDirectory($dir): array
    {
        return $this->filterPrevFromDirectories(scandir($dir));
    }

    /**
     * Filter . and .. directories from directory array
     *
     * @param array $dirs
     * @return array
     */
    protected function filterPrevFromDirectories(array $dirs): array
    {
        return array_values(array_diff($dirs, array('..', '.')));
    }
}