<?php

namespace Framework;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Request;
use Webasics\Framework\Exceptions\InvalidResponseException;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Route\Dispatcher;
use Webasics\Framework\Route\RouteCollection;
use Webasics\Framework\Route\Router;

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
    private static App $instance;

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
    public static function init($environment = self::ENVIRONMENT_PROD): self
    {
        if (!self::$instance) {
            self::$instance = new static($environment);
        }

        return self::$instance;
    }

    /**
     * @throws InvalidResponseException
     * @throws NotFoundException
     */
    public function run()
    {
        $factory = new Psr17Factory();
        $request  = $factory->createRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

        $routeCollection = new RouteCollection();

        $dispatcher = new Dispatcher();

        $router = new Router($dispatcher, $routeCollection);

        $this->registerEnvironmentVariables();
        $this->registerEvents();

        $router->dispatch($request);
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