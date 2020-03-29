<?php

namespace Framework;

/**
 * Class App
 * @package Framework
 */
class App
{

    const ENV_PROD = 'production';
    const ENV_STAGING = 'staging';
    const ENV_DEV = 'development';

    /** @var static */
    private self $instance;

    /** @var string */
    private string $environment;

    /**
     * App constructor.
     * @param string $environment
     */
    private function __construct($environment = self::ENV_PROD)
    {
        $this->environment = $environment;
    }

    /**
     * @param string $environment
     *
     * @return static
     */
    public function create($environment = self::ENV_PROD): self
    {
        if (!$this->instance) {
            $this->instance = new static($environment);
        }

        return $this->instance;
    }

}