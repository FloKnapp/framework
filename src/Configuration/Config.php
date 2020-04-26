<?php

namespace Webasics\Framework\Configuration;

/**
 * Class Config
 * @package Webasics\Framework\Config
 */
class Config
{

    /** @var array */
    private array $config;

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $namespace
     * @return mixed|null
     */
    public function get(string $namespace)
    {
        if ($pos = strpos($namespace, ':') !== false) {
            return $this->resolve($namespace);
        }

        return $this->config[$namespace] ?? null;
    }

    /**
     * @param string $namespace
     * @return mixed|null
     */
    private function resolve(string $namespace)
    {
        $parts   = explode(':', $namespace);
        $pointer = $this->config;

        foreach ($parts as $part) {
            $pointer = $pointer[$part] ?? null;
        }

        return $pointer;
    }

}