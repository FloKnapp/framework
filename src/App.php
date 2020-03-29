<?php

namespace Framework;

/**
 * Class App
 * @package Framework
 */
class App
{

    const EVENT_EXCEPTION = 'app.exception';
    const EVENT_ROUTE_FOUND = 'app.route.found';
    const EVENT_ROUTE_DISPATCH = 'app.route.dispatch';
    const EVENT_CONTROLLER_FOUND = 'app.controller.found';
    const EVENT_CONTROLLER_RENDER = 'app.controller.render';

    const ENVIRONMENT_PROD = 'production';
    const ENVIRONMENT_STAGING = 'staging';
    const ENVIRONMENT_DEV = 'development';

    /** @var static */
    private self $instance;

    /** @var string */
    private string $environment;

    /**
     * App constructor.
     * @param string $environment
     */
    private function __construct($environment = self::ENVIRONMENT_PROD)
    {
        $this->environment = $environment;
    }

    /**
     * @param string $environment
     *
     * @return static
     */
    public function create($environment = self::ENVIRONMENT_PROD): self
    {
        if (!$this->instance) {
            $this->instance = new static($environment);
        }

        return $this->instance;
    }

    public function run()
    {
        $this->registerEnvironmentVariables();
        $this->registerEvents();


    }

    private function registerEnvironmentVariables()
    {

    }

    private function registerEvents()
    {

    }

    private function createRequest()
    {

    }

}